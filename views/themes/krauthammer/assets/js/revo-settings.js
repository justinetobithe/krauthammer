/* https://learn.jquery.com/using-jquery-core/document-ready/ */
jQuery(document).ready(function() {

  /* initialize the slider based on the Slider's ID attribute from the wrapper above */
  jQuery('#rev_slider_1').show().revolution({
    sliderType:"standard",
    fullScreenAlignForce: 'off',
    responsiveLevels:[1200,992,768,576],
    gridwidth:[1200,992,768,576],
    gridheight:[600,600,600,500],
    //responsiveLevels: [1400, 1200, 992, 768, 576],
    //gridwidth:[1180, 992, 890, 720, 610],
    //gridheight:[700, 650, 600, 500, 440],
    visibilityLevels:[1180, 992, 768, 576],
    fullScreenOffsetContainer: '.header',
    sliderLayout:"fullwidth",
    autoHeight:"on",
    //     viewPort: {
    //       enable: true,
    //       outof: 'wait',
    //       visible_area: '80%',
    //       presize: true
    //     },
    navigation: {
      arrows: {
        enable: false,
        style: 'uranus',
        hide_onleave: false,
        hide_onmobile: false,
        tmp: '<h6 class="text-uppercase prevnext f_r_bl t12 c_ffffff"></h6>',
        left : {
          h_align:"center",
          v_align:"bottom",
          h_offset:0,
          v_offset:0,
        },
        right : {
          h_align:"center",
          v_align:"bottom",
          h_offset:0,
          v_offset:0
        }
      },
      bullets: {
        enable: false,
        style: 'hesperiden',
        hide_onleave: true,
        hide_onmobile: true,
        direction: 'vertical',
        h_align: 'right',
        v_align: 'center',
        h_offset: 50,
        v_offset: 0,
        space: 30
      },
    },
    //debugMode: true
  });
});
