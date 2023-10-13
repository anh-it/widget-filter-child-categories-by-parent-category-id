'use strict';
jQuery.noConflict();
jQuery(document).ready(function ($) {
    $('input[id="widget-wpl_wfccbpci-3-id_parent"]').change(function (event){
        const regexCheckNum = new RegExp('^[0-9]+$');

        if(event.target.value===''){
            $('small[id="wfccbpci__session-error"]').removeClass('wfccbpci__session-validate--valid').removeClass('wfccbpci__session-validate--error').text('');
            return;
        }

        if(regexCheckNum.test(event.target.value)){
            // console.log('valid')
            $('small[id="wfccbpci__session-error"]').removeClass('wfccbpci__session-validate--error').addClass('wfccbpci__session-validate--valid').text('Valid');
            $('#widget-wpl_wfccbpci-3-savewidget').prop('disabled', false);

        }else{
            // console.log('invalid');
            $('small[id="wfccbpci__session-error"]').removeClass('wfccbpci__session-validate--valid').addClass('wfccbpci__session-validate--error').text('InValid');
            $('#widget-wpl_wfccbpci-3-savewidget').prop('disabled', true);
        }
    })


});
