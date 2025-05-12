/* ------------------------------------------------------------------------------
 *
 *  # Form validation
 *
 *  Demo JS code for form_validation.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var FormValidation = function () {


    //
    // Setup module components
    //

    // Uniform
    var _componentUniform = function () {
        if (!$().uniform) {
            console.warn('Warning - uniform.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.form-input-styled').uniform({
            fileButtonClass: 'action btn bg-blue'
        });
    };

    // Switchery
    var _componentSwitchery = function () {
        if (typeof Switchery == 'undefined') {
            console.warn('Warning - switchery.min.js is not loaded.');
            return;
        }

        // Initialize single switch
        var elems = Array.prototype.slice.call(document.querySelectorAll('.form-input-switchery'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html);
        });
    };

    // Bootstrap switch
    var _componentBootstrapSwitch = function () {
        if (!$().bootstrapSwitch) {
            console.warn('Warning - bootstrap_switch.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.form-input-switch').bootstrapSwitch({
            onSwitchChange: function (state) {
                if (state) {
                    $(this).valid(true);
                } else {
                    $(this).valid(false);
                }
            }
        });
    };

    // Touchspin
    var _componentTouchspin = function () {
        if (!$().TouchSpin) {
            console.warn('Warning - touchspin.min.js is not loaded.');
            return;
        }

        // Define variables
        var $touchspinContainer = $('.touchspin-postfix');

        // Initialize
        $touchspinContainer.TouchSpin({
            min: 0,
            max: 100,
            step: 0.1,
            decimals: 2,
            postfix: '%'
        });

        // Trigger value change when +/- buttons are clicked
        $touchspinContainer.on('touchspin.on.startspin', function () {
            $(this).trigger('blur');
        });
    };

    // Select2 select
    var _componentSelect2 = function () {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        }

        // Initialize
        var $select = $('.form-control-select2').select2({
            minimumResultsForSearch: Infinity
        });

        // Trigger value change when selection is made
        $select.on('change', function () {
            $(this).trigger('blur');
        });
    };


    jQuery.validator.addMethod("lettersonly", function (value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "You can enter only letters and space");

    jQuery.validator.addMethod("numbersonly", function (value, element) {
        return this.optional(element) || /^[0-9]+$/i.test(value);
    }, "You can enter only numbers");

    jQuery.validator.addMethod("decimalsonly", function (value, element) {
        return this.optional(element) || /^[0-9.]+$/i.test(value);
    }, "You can enter only decimal");



    // Validation config
    var _componentValidation = function () {
        if (!$().validate) {
            console.warn('Warning - validate.min.js is not loaded.');
            return;
        }


        // Initialize
        var validator = $('.form-validate-jquery').validate({
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            errorClass: 'validation-invalid-label',
            successClass: 'validation-valid-label',
            validClass: 'validation-valid-label',
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            // success: function (label) {
            //     label.addClass('validation-valid-label').text('Validate.'); // remove to hide Success message
            // },

            // Different components require proper error label placement
            errorPlacement: function (error, element) {

                // Unstyled checkboxes, radios
                if (element.parents().hasClass('form-check')) {
                    error.appendTo(element.parents('.form-check').parent());
                }

                // Input with icons and Select2
                else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }

                // Input group, styled file input
                else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                    error.appendTo(element.parent().parent());
                }

                // Other elements
                else {
                    error.insertAfter(element);
                }
            },
            rules: {
                password: {
                    minlength: 5
                },
                repeat_password: {
                    equalTo: '#password'
                },
                email: {
                    email: true
                },
                repeat_email: {
                    equalTo: '#email'
                },
                minimum_characters: {
                    minlength: 10
                },
                maximum_characters: {
                    maxlength: 10
                },
                minimum_number: {
                    min: 10
                },
                maximum_number: {
                    max: 10
                },
                number_range: {
                    range: [10, 20]
                },
                url: {
                    url: true
                },
                date: {
                    date: true
                },
                date_iso: {
                    dateISO: true
                },
                numbers: {
                    number: true
                },
                digits: {
                    digits: true
                },
                creditcard: {
                    creditcard: true
                },
                basic_checkbox: {
                    minlength: 2
                },
                styled_checkbox: {
                    minlength: 2
                },
                switchery_group: {
                    minlength: 2
                },
                switch_group: {
                    minlength: 2
                },

                // image: {
                //     accept: "jpg|jpeg|png|gif"
                // },


                // custom rules

                no_of_positions: {
                    number: true
                },

                zip_code: {
                    number: true
                },
                pec: {
                    number: true,
                    min: 6
                },

                organization_name: {
                    lettersonly: true
                },
                first_name: {
                    lettersonly: true
                },
                focal_person_first_name: {
                    lettersonly: true
                },
                focal_person_last_name: {
                    lettersonly: true
                },

                intern_first_name : {
                    lettersonly: true
                },
                intern_last_name : {
                    lettersonly: true
                }



            },
            messages: {
                custom: {
                    required: 'This is a custom error message'
                },
                repeat_password: {
                    equalTo: 'Your password and repeat password do not match'
                },
                repeat_email: {
                    equalTo: 'Your email and repeat email do not match'
                },
                basic_checkbox: {
                    minlength: 'Please select at least {0} checkboxes'
                },
                styled_checkbox: {
                    minlength: 'Please select at least {0} checkboxes'
                },
                switchery_group: {
                    minlength: 'Please select at least {0} switches'
                },
                switch_group: {
                    minlength: 'Please select at least {0} switches'
                },
                agree: 'Please accept our policy'
            },
        });



        var validator = $('.form-validate-jquery-confirmation').validate({
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            errorClass: 'validation-invalid-label',
            successClass: 'validation-valid-label',
            validClass: 'validation-valid-label',
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },

            // Different components require proper error label placement
            errorPlacement: function (error, element) {

                // Unstyled checkboxes, radios
                if (element.parents().hasClass('form-check')) {
                    error.appendTo(element.parents('.form-check').parent());
                }

                // Input with icons and Select2
                else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }

                // Input group, styled file input
                else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                    error.appendTo(element.parent().parent());
                }

                // Other elements
                else {
                    error.insertAfter(element);
                }
            },
            rules: {
                password: {
                    minlength: 5
                },
                repeat_password: {
                    equalTo: '#password'
                },
                email: {
                    email: true
                },
                repeat_email: {
                    equalTo: '#email'
                },
                minimum_characters: {
                    minlength: 10
                },
                maximum_characters: {
                    maxlength: 10
                },
                minimum_number: {
                    min: 10
                },
                maximum_number: {
                    max: 10
                },
                number_range: {
                    range: [10, 20]
                },
                url: {
                    url: true
                },
                date: {
                    date: true
                },
                date_iso: {
                    dateISO: true
                },
                numbers: {
                    number: true
                },
                digits: {
                    digits: true
                },
                creditcard: {
                    creditcard: true
                },
                basic_checkbox: {
                    minlength: 2
                },
                styled_checkbox: {
                    minlength: 2
                },
                switchery_group: {
                    minlength: 2
                },
                switch_group: {
                    minlength: 2
                },

                // image: {
                //     accept: "jpg|jpeg|png|gif"
                // },


                // custom rules

                no_of_positions: {
                    number: true
                },

                zip_code: {
                    number: true
                },
                pec: {
                    number: true,
                    min: 6
                },

                organization_name: {
                    lettersonly: true
                },
                first_name: {
                    lettersonly: true
                },
                focal_person_first_name: {
                    lettersonly: true
                },
                focal_person_last_name: {
                    lettersonly: true
                },

                intern_first_name : {
                    lettersonly: true
                },
                intern_last_name : {
                    lettersonly: true
                }



            },
            messages: {
                custom: {
                    required: 'This is a custom error message'
                },
                repeat_password: {
                    equalTo: 'Your password and repeat password do not match'
                },
                repeat_email: {
                    equalTo: 'Your email and repeat email do not match'
                },
                basic_checkbox: {
                    minlength: 'Please select at least {0} checkboxes'
                },
                styled_checkbox: {
                    minlength: 'Please select at least {0} checkboxes'
                },
                switchery_group: {
                    minlength: 'Please select at least {0} switches'
                },
                switch_group: {
                    minlength: 'Please select at least {0} switches'
                },
                agree: 'Please accept our policy'
            },

            submitHandler: function(form) {
                Swal.fire({

                    title: 'Are you sure?',

                    text: "You want to submit the registration!",

                    showCancelButton: true,

                    confirmButtonColor: '#3085d6',

                    cancelButtonColor: '#d33',

                    confirmButtonText: "Yes",

                    cancelButtonText: "No"

                }).then((result) => {

                    if (result.isConfirmed) {
                        // if the user clicked the "Submit" button in the popup, submit the form
                        form.submit();
                    } else {
                        // if the user clicked the "Cancel" button in the popup, do nothing
                        return false;
                    }
                });

            }
        });

        // Reset form
        $('#reset').on('click', function () {
            validator.resetForm();
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentUniform();
            _componentSwitchery();
            _componentBootstrapSwitch();
            _componentTouchspin();
            _componentSelect2();
            _componentValidation();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    FormValidation.init();
});
