$(document).ready(function(){
      // Add smooth scrolling to all links
      $(".contact-us-banner a.btnInterested").on('click', function(event) {
    
        // Make sure this.hash has a value before overriding default behavior
        if (this.hash !== "") {
          // Prevent default anchor click behavior
          event.preventDefault();
    
          // Store hash
          var hash = this.hash;
    
          // Using jQuery's animate() method to add smooth page scroll
          // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
          $('html, body').animate({
            scrollTop: $(hash).offset().top
          }, 800, function(){
       
            // Add hash (#) to URL when done scrolling (default click behavior)
            window.location.hash = hash;
          });
        } // End if
      });
      
    //   $('.pagination__item a').click(function(e){
    //       e.preventDefault();
          
    //       var pageNo = $(this).val();
    //       alert(pageNo);
          
        //   $.ajax({
        //     type : 'POST',
        //      data : { pageNo: pageNo },
        //      url  : baseURL + 'cms/functions/get_pagination/',
        //      success: function ( data ) {
        //         alert(data);
        //       }
        //   });
    //   });
      
    });

/* -------------------- END User functions --------------------  */
function headerAffixate(mainMenu) {
  $(mainMenu).affix({
    offset: {
      top: function () { return $(mainMenu).outerHeight(); }
    }
  });
}

function scrollAnimation(scroller) {
  $(scroller).on('click', function (e) {
    e.preventDefault();

    if (typeof $(this.hash).offset() !== "undefined") {
      $("html, body").animate({
        scrollTop: $(this.hash).offset().top
      }, 300);
    }
  });
}

function datepickers(fields) {
  $(fields.field).datepicker(fields.setting);
}

function initIsotope(isotope) {
  var filters = [];

  // filter functions
  var filterFns = {
    // show if number is greater than 50
    numberGreaterThan50: function () {
      var number = $(this).find('.number').text();
      return parseInt(number, 10) > 50;
    },
    // show if name ends with -ium
    ium: function () {
      var name = $(this).find('.name').text();
      return name.match(/ium$/);
    }
  };

  var $grid = $(isotope.grid).isotope({
    itemSelector: isotope.item,
    layoutMode: isotope.layout,
    cellsByRow: {
      rowHeight: isotope.cellsbyrow_height
    }
  });

  if (isotope.selectType === 'button') {
    $(isotope.tabButtons).on('click', function () {
      //$('.options li a.active').removeClass('active');

      $(this).toggleClass('active');
      var filter = $(this).attr('data-filter');

      $grid.isotope({ filter: '.' + filter });
    });
  }
  else if (isotope.selectType === 'select') {
    $(isotope.selector).on('change', function () {
      // get filter value from option value
      var filterValue = this.value;
      // use filterFn if matches value
      filterValue = filterFns[filterValue] || filterValue;
      $grid.isotope({ filter: '.' + filterValue });
    });
  }

}

function initContactForm(contactForms) {
  $(contactForms).removeAttr("novalidate");
  $(contactForms).submit(function (e) {
    e.preventDefault();

    $.post(baseURL + "cf/submit/", $(this).serializeArray(), function (data) {
      if (data.status == "error") {
        $.toast({
          heading: 'Warning',
          text: data.message,
          showHideTransition: 'plain',
          icon: 'warning'
        });
      }
      else {
        $.toast({
          heading: 'Success',
          text: 'Your message has been sent!',
          showHideTransition: 'plain',
          icon: 'success'
        });

        $(contactForms)[0].reset();
      }
    });
  });
} 


function initProgressBar(bar) {
  bar.forEach(function (element) {
    $(element.hook).progressbar({
      value: element.rate
    });
  });
}

function scrollSpyInit(scrollSpy) {
  ScrollPosStyler.init({
    scrollOffsetY: scrollSpy.scrollOffsetY
  });
}

function resizer(resizeDiv) {
  var referElem = document.getElementsByClassName(resizeDiv.referenceDiv);
  var element = document.getElementsByClassName(resizeDiv.changedDiv);

  new ResizeSensor(referElem, function () {
    element[0].style.height = referElem[0].clientHeight;
  });
}

function initElementAdder(item) {
  if (document.getElementById(item.target.parentId) != null) {
    var element = document.getElementById(item.target.parentId).getElementsByClassName(item.target.child);
    var children = element[0].children;
    var i = 0;


    do {
      var text = document.createTextNode(("0" + (i + 1)).slice(-2));

      var node = document.createElement(item.element);
      node.className = item.classes;
      node.appendChild(text);

      var place = children[i].getElementsByClassName(item.target.place);

      place[0].appendChild(node);

      i++;
    } while (i < children.length);
  }
}

function floaterSlider(slider) {

  if (window.screen.availWidth < 992) {
    var view = $(slider.view);

    if (window.screen.availWidth <= 576) {
      var sliderLimit = slider.sm.limit;
      var move = slider.sm.move;
    }
    else if (window.screen.availWidth >= 577 && window.screen.availWidth <= 768) {
      var sliderLimit = slider.md.limit;
      var move = slider.md.move;
    }
    else if (window.screen.availWidth >= 769 && window.screen.availWidth <= 992) {
      var sliderLimit = slider.lg.limit;
      var move = slider.lg.move;
    }
    else if (window.screen.availWidth > 993) {
      var sliderLimit = slider.xl.limit;
      var move = slider.lg.move;
    }

    $(slider.left).click(function () {
      var currentPosition = parseInt(view.css("left"));
      if (currentPosition >= sliderLimit) view.stop(false, true).animate({ left: "-=" + move }, { duration: 100 })
    });

    $(slider.right).click(function () {
      var currentPosition = parseInt(view.css("left"));
      if (currentPosition < 0) view.stop(false, true).animate({ left: "+=" + move }, { duration: 100 })
    });

  }
}


  // untested until CMS conversions
function initContactForm(contactForms) {
	$(contactForms).removeAttr("novalidate");
	$(contactForms).submit(function (e) {
		e.preventDefault();
        alert('Your message has been sent!');
		$.post( baseURL+"cf/submit/", $(this).serializeArray(), function( data ) {
			if (data.status == "error" ) {
				$.toast({
					heading: 'Warning',
					text: data.message,
					showHideTransition: 'plain',
					icon: 'warning'
				}); 
			}
			else {
				$.toast({
					heading: 'Success', 
					text: 'Your message has been sent!',
					showHideTransition: 'plain',
					icon: 'success' 
				}); 
				$(contactForms)[0].reset();
			}
		});
	});
}
 


// untested until CMS conversions
function initNewsletterForm(newsForm) {
	$(newsForm).removeAttr("novalidate");
	$(newsForm).submit(function (e) {
		e.preventDefault();

		$.post( baseURL+"cf/news_submit/", $(this).serializeArray(), function( data ) {
			if (data.status == "error" ) {
				$.toast({
					heading: 'Warning',
					text: data.message,
					showHideTransition: 'plain',
					icon: 'warning'
				});
			}
			else {
				$.toast({
					heading: 'Success',
					text: 'Your message has been sent!',
					showHideTransition: 'plain',
					icon: 'success'
				});

				$(newsForm)[0].reset();
			}
		});
	});
}


// const cookieContainer = document.querySelector(".cookie-container");
// const cookieButton = document.querySelector(".cookie-btn");

// cookieButton.addEventListener("click", () => {
//   cookieContainer.classList.remove("active");
//   localStorage.setItem("cookieBannerDisplayed", "true");
// });

// setTimeout(() => {
//   if (!localStorage.getItem("cookieBannerDisplayed")) {
//     cookieContainer.classList.add("active");
//   }
// }, 2000);


function addClass(classAdder) {
  $.each(classAdder, function (key, item) {
    $(item.value).addClass(item.classes);
  });
}
 

/* -------------------- END User functions --------------------  */
$(document).ready(function () {
  const settings = {
    scrollSpy: {
      scrollOffsetY: $('.header').height()
    },
    classAdder: [
      {
        value: '.navbar-nav li',
        classes: 'nav-item'
      },
      {
        value: '.navbar-nav li a',
        classes: 'nav-link'
      },
      // {
      //   value: 'footer .col-6:first-of-type .link-list li a',
      //   classes: 'link w-100'
      // },
      // {
      //   value: 'footer .col-6:nth-of-type(2) .link-list li a',
      //   classes: 'link w-100'
      // }
    ],
    /*
    floaterSlider: {
      view: ".floaterSlider",
      left: ".leftArrow",
      right: ".rightArrow",
      sm: {
        limit: -630,
        move: "180px",
      },
      md: {
        limit: -290,
        move: "360px",
      },
      lg: {
        limit: -280,
        move: "540px",
      },
      xl: {
        limit: -630,
        move: "180px",
      },
    },
    elementAdder: {
      target: {
        parentId: "rev_slider_1",
        child: "tp-bullets",
        place: "tp-bullet-title"
      },
      element: 'h6',
      classes: 'count f_m_b t18 c_ffffff',
      text: '[counter]'
    },
    datefields: {
      field: '#pdate',
      settings: {
        'format': 'MM d, yyyy'
      }
    },
     */
    //     isotope: [
    //       {
    //         selectType: 'button',
    //         tabButtons: '.btn.all, .btn.meal, .btn.pizza, .btn.dessert, .btn.fish, .btn.meat, .btn.burger, .btn.wine',
    //         itemSelector: '.products .item',
    //         layout: 'fitRows',
    //         cellsbyrow_height: 344,
    //         grid: '.grid',
    //         selector: '.products .choices',
    //         item: '.products .item',
    //       },
    //       {
    //         selectType: 'select',
    //         selector: '#select-accomodation',
    //         item: '.accomodations .item',
    //         layout: 'fitRows',
    //         grid: '.grid'
    //       }
    //     ],
    /*
    datefields: {
      field: '#calendar-field',
      setting: {
        showOtherMonths: true,
        selectOtherMonths: true,
        dayNamesMin: [ "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat" ]
      }
    },
    isotope: {
      tabButtons: '.btn.all, .btn.install, .btn.repair',
      item: '.grid-item',
      layout: 'fitRows',
      cellsbyrow_height: 215,
      grid: '.grid'
    },
    resizeDiv: {
      referenceDiv: 'navbar-collapse',
      changedDiv: 'header'
    },
    bars: [
      {
        hook: '.bar1',
        rate: 90
      },
      {
        hook: '.bar2',
        rate: 85
      },
      {
        hook: '.bar3',
        rate: 95
      },
      {
        hook: '.bar4',
        rate: 92
      }
    ]
     */
  };

  scrollSpyInit(settings.scrollSpy);
 
  addClass(settings.classAdder);
  initContactForm(settings.contactForms);
  initNewsletterForm(settings.newsForm);
  //initElementAdder(settings.elementAdder);
  //datepickers(settings.datefields);
  //initIsotope(settings.isotope[0]);
  //resizer(settings.resizeDiv);
  //initProgressBar(settings.bars);
  //headerAffixate(settings.menuDiv);
  //initContactForm(settings.contactForms);
  //scrollAnimation(settings.scroller);


});
