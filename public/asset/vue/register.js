$(function () {
    console.log('Vue is loaded.');
    const resgisterVueElement = new Vue({
        el: '#vue__application',
        data: {
            isCpf: true,
        },
        methods: {
            setJqueryMasks: function () {
                $('.mask__cpf').mask('999.999.999-99');
                $('.mask__cnpj').mask('99.999.999/9999-99');
                $('.mask__phone').mask('(99) 9 9999-9999');
            },
            validateNumber: function (event) {
                if (!(event.key.match(/[0-9]|Backspace/) || event.ctrlKey)) {
                    event.preventDefault();
                }
            }
        },
        watch: {
            isCpf() {
                $('.mask__cpf').val("");
                $('.mask__cnpj').val("");
            }
        },
        mounted() {
            this.$nextTick(() => {
                if (this.$refs.registrationForm) {
                    this.$refs.cpjCnpjFields.hidden = false;
                    this.setJqueryMasks();
                }
            });
        }
    });
}, Vue);