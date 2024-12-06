

// Đối tượng Validator
function Validator(options) {

    function getParent(element, selector) {
        while (element.parentElement) {
            if(element.parentElement.matches(selector)) {
                return element.parentElement;
            }

            element = element.parentElement;
        }
    }

    let selectorRules = {};

    // Hàm thực hiện validate
    function Validate(inputElement, rule) {
        let errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector);
        let errorMessage;

        // Lấy ra các rules của selector
        let rules = selectorRules[rule.selector];

        // Lặp lại các rules & kiểm tra
        // Nếu có lỗi thì dừng việc kiểm tra
        for(const element of rules) {
            switch(inputElement.type) {
                case 'radio':
                case 'checkbox':
                    errorMessage = element(
                        formElement.querySelector(`${rule.selector}:checked`)
                    );
                break;

                default:
                    errorMessage = element(inputElement.value);
            }
            if(errorMessage) break
        }

        if(errorMessage) {
            errorElement.innerText = errorMessage;
            getParent(inputElement, options.formGroupSelector).classList.add('invalid');
        } else {
            errorElement.innerText = '';
            getParent(inputElement, options.formGroupSelector).classList.remove('invalid');
        }

        return !errorMessage;
    }

    // Lấy element của form cần validate
    let formElement = document.querySelector(options.form);

    if(formElement) {

        formElement.onsubmit = function(e) {
            e.preventDefault();
            
            let isFormValid = true;    

            // Lặp qua từng rules và Validate
            options.rules.forEach(function(rule) {
                let inputElement = formElement.querySelector(rule.selector);
                let isValid = Validate(inputElement, rule);

                if(!isValid) {
                    isFormValid = false;
                }
            });

            if(isFormValid) {
                // Trường này submit với javascript
                if(typeof options.onSubmit === 'function') {
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

                    options.onSubmit(formValues);
                }
                // Trường này submit với hành vi mặc định
                else {
                    formElement.submit();
                }
            }
        }

        // Xử lý lặp ra mỗi rules và xử lý (lắng nghe sự kiện)
        options.rules.forEach(function(rule) {

            // lưu lại các rules cho mỗi input
            if(Array.isArray(selectorRules[rule.selector])) {
                selectorRules[rule.selector].push(rule.test);
            } else {                
                selectorRules[rule.selector] = [rule.test];
            }

            let inputElements = formElement.querySelectorAll(rule.selector);

            Array.from(inputElements).forEach(function(inputElement) {
                // Xử lý trường hợp blur khỏi input
                inputElement.onblur = function() {                
                    Validate(inputElement, rule);
                }

                // Xử lý mỗi khi người dùng nhập vào input
                inputElement.oninput = function() {
                    let errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector);
                    errorElement.innerText = '';
                    getParent(inputElement, options.formGroupSelector).classList.remove('invalid');
                }
            });
        });

    }
}


// Định nghĩa rules
//Nguyên tắc của các rules:
// 1. Khi có lỗi => trả ra messae lỗi
// 2. Khi hợp lệ => không trả ra cái gì (undefined)
Validator.isRequired = function(selector, message) {
    return {
        selector: selector,
        test: function(value) {
            return value ? undefined : message || 'Vui lòng nhập trường này';
        }
    };
}

Validator.isEmail = function(selector, message) {
    return {
        selector: selector,
        test: function(value) {
            let regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            return regex.test(value) ? undefined : message || 'Trường này phải là email';
        }
    };
}

Validator.minLength = function(selector, min, message) {
    return {
        selector: selector,
        test: function(value) {
            return value.length >= min ? undefined : message || `Vui lòng nhập tối thiểu ${min} kí tự`;
        }
    };
}

Validator.isConfirmed = function(selector, getConformValue, message) {
    return {
        selector: selector,
        test: function(value) {
            return value === getConformValue() ? undefined : message || 'Giá trị nhập vào không chính xác';
        }
    };
}