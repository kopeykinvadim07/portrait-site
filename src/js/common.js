(function ($) {
  $(document).ready(function () {

    if ($(window).width() < 992) {
      $('.hamburger-btn__input').on('change', function () {
        $('.header-menu').slideToggle();
      });

      $('.menu-item-has-children > a').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        $this.next().slideToggle();
      });
      $(document).on('click', function (e) {
        if (!$(e.target).closest(".menu-item-has-children").length) {
          $('.sub-menu').slideUp();
        }
        e.stopPropagation();
      });
    } else {
      function menu_drop_donw(menu) {
        var menu = $(menu),
          has_menu = menu.find('.menu-item-has-children');

        if ($(window).width() > 960) {
          has_menu.hover(
            function () {
              $(this).toggleClass('active').find('.sub-menu').stop(true, false).slideDown('medium');
            },
            function () {
              $(this).toggleClass('active').find('.sub-menu').stop(true, false).slideUp('medium');
            }
          );
        } else {
          has_menu.on('click', function () {
            $(this).toggleClass('active').find('.sub-menu').stop(true, false).slideToggle('medium');
          });
        }
      }

      menu_drop_donw('.header-menu__wrap');
    }

    if ($('.cd-upload-btn').length) {
      $('.codedropz-upload-inner h3').html('Drag and Drop <br>or Browse files here. ')
      $('.cd-upload-btn').html('Maximum of 2 photos.');
    }

    if ($('.portrait-block').length)  {
      
      portraitMainSlider();
      portraitInnerSlider();

      $('body').on('click', '.portrait-block__rand', function() {
        let el = $(this),
            parent = el.closest('.portrait-block__card'),
            sliderTotal = parent.find('.portrait-card').length,
            index = parseInt(parent.find('.portrait-card.slick-current').attr('data-slick-index')),
            rand = randomExcluded(0, sliderTotal - 1, parseInt(index)),
            slider = parent.find('.portrait-block__slider');
            
        index += 1;

        if (index !== parseInt(parent.attr('data-post-count'))) {
          parent.find('.slick-next-card').click();
        } else {
          slider.slick('slickGoTo', rand);
        }

      });

      $('.portrait-block').on('click', '.slick-next-card', function() {
        let el         = $(this),
            parent     = el.closest('.portrait-block__card'),
            allLength  = parent.attr('data-post-count'),
            cardLength = parent.find('.portrait-card').length;

        if (el.hasClass('slick-disabled') && !el.hasClass('disable-ajax')) {
          if (cardLength < allLength) {
            var data = {
              action: 'portrait_add_slide_ajax',
              current_ids: JSON.parse(parent.attr('data-current-ids')),
              cat_id: parent.attr('data-cat-id'),
              nonce: parent.attr('data-nonce')
            };
            $.ajax({
              type: 'POST',
              data: data,
              url: myajax.url,
              beforeSend: function() {
                el.addClass('disable-ajax');
              },
              success: function( response ) {
                let result = JSON.parse(response);

                if (result.status) {
                  // console.log(result);
                  parent.attr('data-current-ids', JSON.stringify(result.current_ids));
                  parent.find('.portrait-block__slider').slick('slickAdd', result.html);
                  portraitInnerSlider();
                  cardLength += 1;
                  if (parseInt(cardLength) === parseInt(allLength)) {
                    parent.addClass('max');
                  }
                }
                el.removeClass('disable-ajax');
              }
            });
          }
        }

      });

      

    $(".portrait-block").on('click', '.portrait-card__read-story', function() {
      let modalSliderContent = $('.read-more-modal').find('.read-more-modal__content');
      if (modalSliderContent.hasClass('slick-initialized')) {
        modalSliderContent.slick('unslick');
      }
      let el = $(this),
          data = JSON.parse(el.attr('data-portrait')),
          parent = el.closest('.portrait-block__card'),
          max = parseInt(parent.attr('data-post-count')),
          html = modalHtml(data);

      $('#readMore .read-more-modal__content').attr({
        'data-current-ids': JSON.stringify([data.id]),
        'data-max': max,
        'data-cat-id': parent.attr('data-cat-id')
      }).html(html);
      $('.read-more-modal__current').html('1');
      $('.read-more-modal__max').html(max);
      $('#readMore').modal('toggle');
      
      
      setTimeout(() => {
        modalSlider();
      }, 1000);

      if (max > 1) {
        var dataAjax = {
          action: 'portrait_add_modal_slide_ajax',
          current_ids: JSON.parse(modalSliderContent.attr('data-current-ids')),
          cat_id: parent.attr('data-cat-id'),
          nonce: parent.attr('data-nonce')
        };
        $.ajax({
          type: 'POST',
          data: dataAjax,
          url: myajax.url,
          beforeSend: function() {
            modalSliderContent.slick({
              dots: false,
              infinite: false,
              arrows: true,
              speed: 300,
              slidesToShow: 1,
              slidesToScroll: 1,
              touchMove: false,
              draggable: false,
              swipe: false,
              prevArrow: '<button type="button" class="slick-arrow-main slick-prev-card-main"></button>',
              nextArrow: '<button type="button" class="slick-arrow-main slick-next-card-main"><span></span></button>'
            });
            el.addClass('disable-ajax');
          },
          success: function( response ) {
            let result = JSON.parse(response);
            // console.log(result);
            if (result.status) {
              modalSliderContent.attr('data-current-ids', JSON.stringify(result.fields.current_ids));
              modalSliderContent.slick('slickAdd', modalHtml(result.data));
              modalSlider();
            }
            el.removeClass('disable-ajax');
          }
        });
      }
    });

    $('.read-more-modal').on('click', '.slick-next-card-main', function() {
      let modalSliderContent = $('.read-more-modal').find('.read-more-modal__content');

  
      let el = $(this),
          max = modalSliderContent.attr('data-max'),
          cardLength = modalSliderContent.find('.read-more-modal__card').length;

      if (el.hasClass('slick-disabled') && !el.hasClass('disable-ajax')) {
        if (cardLength < max) {
          var dataAjax = {
            action: 'portrait_add_modal_slide_ajax',
            current_ids: JSON.parse(modalSliderContent.attr('data-current-ids')),
            cat_id: modalSliderContent.attr('data-cat-id'),
            nonce: modalSliderContent.attr('data-nonce')
          };
          
          $.ajax({
            type: 'POST',
            data: dataAjax,
            url: myajax.url,
            beforeSend: function() {
              el.addClass('disable-ajax');
            },
            success: function( response ) {
              let result = JSON.parse(response);
              console.log(result);
              if (result.status) {
                modalSliderContent.attr('data-current-ids', JSON.stringify(result.fields.current_ids));
                modalSliderContent.slick('slickAdd', modalHtml(result.data));
                modalSlider();
              }
              el.removeClass('disable-ajax');
            }
          });
        }
      }
    });

    $('.portrait-block__load-more').on('click', function() {
      let el = $(this),
          dataAjax = {
            action: 'portrait_load_more_ajax',
            current_ids: JSON.parse(el.attr('data-cats')),
            max: el.attr('data-max'),
            count: el.attr('data-count'),
            nonce: el.attr('data-nonce')
          };
      if (!el.hasClass('disable-ajax')) {
        $.ajax({
          type: 'POST',
          data: dataAjax,
          url: myajax.url,
          beforeSend: function() {
            el.addClass('disable-ajax');
          },
          success: function( response ) {
            let result = JSON.parse(response);
            if (result.status) {
              $('.portrait-block__wrap').append(result.html);
              el.attr({
                'data-cats': JSON.stringify(result.fields.current_ids),
                'data-count': result.fields.count
              });

              portraitInnerSlider();
              portraitMainSlider();

            }
            if (result.last === true) {
              el.hide();
            }
            el.removeClass('disable-ajax');
          }
        });
      }
    });

    function randomExcluded(min, max, excluded) {
      var n = Math.floor(Math.random() * (max-min) + min);
      if (n >= excluded) n++;
      return n;
    }

    function portraitMainSlider() {
      var loadSlider = $(".portrait-block__slider:not(.slick-initialized)");
          loadSlider.slick({
            dots: false,
            infinite: false,
            arrows: true,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
            touchMove: false,
            draggable: false,
            swipe: false,
            prevArrow: '<button type="button" class="slick-arrow slick-prev-card"></button>',
            nextArrow: '<button type="button" class="slick-arrow slick-next-card"><span></span></button>'
          });
      loadSlider.on('afterChange', function(event, slick, currentSlide, nextSlide) {
        if ($(slick.$slider).hasClass('portrait-block__slider')) {
          $(this).closest('.portrait-block__card').find('.portrait-block__rand span').html(currentSlide + 1);
        }
      });
    };

    function portraitInnerSlider() {
      $(".portrait-card__images:not(.slick-initialized)").slick({
        dots: true,
        infinite: false,
        arrows: false,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        touchMove: false,
        draggable: false,
        swipe: false,
      });
    }

    function modalHtml(data) {
      return `<div class="read-more-modal__card"><div class="row">
        <div class="col-md-5">
          <div class="read-more-modal__images">
            ${data.images}
          </div>
        </div>
        <div class="col-md-7">
          <div class="read-more-modal__right">
            <div class="read-more-modal__row">
              <div class="read-more-modal__name">${data.name}</div>
            </div>
            <div class="read-more-modal__city">${data.city}</div>
            <div class="read-more-modal__text">${data.text}</div>
          </div>
        </div>
      </div></div>`;
    }

    function modalSlider() {
      $('.read-more-modal').find('.read-more-modal__images:not(.slick-initialized)').slick({
        dots: true,
        infinite: false,
        arrows: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        touchMove: false,
        draggable: false,
        swipe: false,
        prevArrow: '<button type="button" class="slick-arrow slick-prev-modal"></button>',
        nextArrow: '<button type="button" class="slick-arrow slick-next-modal"></button>'
      });
    }

    $('.read-more-modal__content').on('afterChange', function(event, slick, currentSlide, nextSlide) {
      if ($(slick.$slider).hasClass('read-more-modal__content')) {
        $(this).closest('.modal-body').find('.read-more-modal__current').html(currentSlide + 1);
      }
    });

  }


  let wordLen = 1000,
		  len;
  $(".textarea-label textarea").on('keyup change', function(event){
    len = $(this).val().length;
    $(".textarea-country").text((len) + "/"+ wordLen +" character/spaces");
  });


  });
})(jQuery);


 