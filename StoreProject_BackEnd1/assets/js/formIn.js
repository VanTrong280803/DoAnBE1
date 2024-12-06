

function Validator(formSelector) {
    let _this = this;
    let formRlues = {};

    function getParent(element, selector) {
        while (element.parentElement) {
            if (element.parentElement.matches(selector)) {
                return element.parentElement;
            }
            element = element.parentElement;
        }
    }

    /**
     * Quy ước tạo rule:
     * - nếu có lỗi thì return `error message`
     * - Nếu không có lỗi thì return `undefined`
     */
    let validatorRules = {
        required: function (value) {
            return value ? undefined : `Vui lòng nhập trường này`;
        },
        email: function (value) {
            let regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            return regex.test(value) ? undefined : `Vui lòng nhập đúng email`;
        },
        min: function (min) {
            return function (value) {
                return value.length >= min ? undefined : `Vui lòng nhập ít nhất ${min} kí tự`;
            }
        },
        max: function (max) {
            return function (value) {
                return value.length <= max ? undefined : `Vui lòng nhập tối đa ${max} kí tự`;
            }
        }
    };
    
    // Lấy ra form element trong DOM theo `formSelector`
    let formElement = document.querySelector(formSelector);

    // Chỉ xử lý khi có element trong DOM
    if (formElement) {

        let inputs = formElement.querySelectorAll('[name][rules]');
        for (let input of inputs) {

            let rules = input.getAttribute('rules').split('|');
            for (let rule of rules) {
                let ruleInfo;
                let isRuleHasValue = rule.includes(':');

                if (isRuleHasValue) {
                    ruleInfo = rule.split(':');
                    rule = ruleInfo[0];
                }

                let ruleFunc = validatorRules[rule];
                if (isRuleHasValue) {
                    ruleFunc = ruleFunc(ruleInfo[1]);
                }

                if (Array.isArray(formRlues[input.name])) {
                    formRlues[input.name].push(ruleFunc);
                } else {
                    formRlues[input.name] = [ruleFunc];
                }
            }

            // Lắng nghe sk để validate (blur, change, ...)
            input.onblur = handleValidate;
            input.oninput = handleClearError;
        }

        // Hàm thực hiện validate
        function handleValidate (event) {
            let rules = formRlues[event.target.name];
            let errorMessage;

            for (let rule of rules) {
                errorMessage = rule(event.target.value);
                if (errorMessage) break;
            }

            // Nếu có lỗi thì hiển thị message lỗi ra UI
            if (errorMessage) {
                let formGroup = getParent(event.target, '.form-group');

                if (formGroup) {
                    formGroup.classList.add('invalid');
                    let formMessage = formGroup.querySelector('.form-message');
                    if (formMessage) {
                        formMessage.innerText = errorMessage;
                    }
                }
            }

            return !errorMessage;
        }

        // Hàm clear message lỗi
        function handleClearError (event) {
            let formGroup = getParent(event.target, '.form-group');
            if (formGroup.classList.contains('invalid')) {
                formGroup.classList.remove('invalid');

                let formMessage = formGroup.querySelector('.form-message');
                if (formMessage) {
                    formMessage.innerText = '';
                }
            }
        }
    }

    // Xử lý hành vi submit form
    formElement.onsubmit = function (event) {
        event.preventDefault();

        let inputs = formElement.querySelectorAll('[name][rules]');
        let isValid = true;

        for (let input of inputs) {
            if (!handleValidate({ target: input })) {
                isValid = false;
            }
        }

        // Khi không có lỗi thì submit form
        if (isValid) {
            if (typeof _this.onSubmit === 'function') {
                let enableInputs = formElement.querySelectorAll('[name]:not([disabled])')
                    let formValues = Array.from(enableInputs).reduce(function(values, input) {
                        
                        switch(input.type) {
                            case 'radio':
                                values[input.name] = formElement.querySelector('input[name="' + input.name + '"]:checked').value;
                                break;
                            case 'checkbox':
                                if(!values[input.name]) {
                                    values[input.name] = []
                                }
                                if(input.matches(':checked')) {
                                    values[input.name].push(input.value)
                                }
                                break
                            case 'file':
                                values[input.name] = input.files;
                                break;
                            default:
                                values[input.name] = input.value;
                        }

                        return values;
                    }, {});

                    // Gọi lại hàm onSubmit và trả về giá trị của form
                    _this.onSubmit(formValues);
            } else {
                formElement.submit();
            }
        }
    }
}