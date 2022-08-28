$(function () {
    console.log('Vue is loaded.');
    const helpVueElement = new Vue({
        el: '#vue__application',
        data: {
            // 0 for ecommerce, 1 for restaurants
            state: 0,
            base: "https://suportevamo.zendesk.com/hc/pt-br/articles",
            ecommerces: [
                {
                    link: "/1260806614709-Quais-s%C3%A3o-as-modalidades-de-pagamento-para-meu-e-commerce-",
                    title: "Quais são as modalidades de pagamento para meu e-commerce?",
                },
                {
                    link: "/1260806640550-Recebi-o-e-mail-SMS-e-ainda-n%C3%A3o-fizeram-minha-entrega-e-agora-",
                    title: "Recebi o e-mail/SMS e ainda não fizeram minha entrega, e agora?",
                },
                {
                    link: "/1260806614649-Como-crio-uma-conta-e-commerce-",
                    title: "Como crio uma conta e-commerce?",
                },
                {
                    link: "/1260806640390-Posso-agendar-o-meu-pedido-",
                    title: "Posso agendar o meu pedido?",
                },
            ],
            restaurants: [
                {
                    link: "/1260806614829-O-mensageiro-n%C3%A3o-encontrou-a-pessoa-que-ir%C3%A1-receber-a-entrega-e-agora-",
                    title: "O mensageiro não encontrou a pessoa que irá receber a entrega, e agora?",
                },
                {
                    link: "/1260806640630-Como-eu-cancelo-um-pedido-j%C3%A1-em-andamento-",
                    title: "Como eu cancelo um pedido já em andamento?",
                },
                {
                    link: "/1260806614769-Como-sei-que-o-pedido-foi-entregue-",
                    title: "Como sei que o pedido foi entregue?",
                },
                {
                    link: "/1260806614729-Qual-o-tamanho-m%C3%A1ximo-permitido-",
                    title: "Qual o tamanho máximo permitido?",
                },
                {
                    link: "/1260806614269-Pagamentos-pela-m%C3%A1quina-de-cart%C3%A3o",
                    title: "Pagamentos pela máquina de cartão",
                },
                {
                    link: "/1260806640050-Taxa-de-retorno-para-pagamentos-em-dinheiro",
                    title: "Taxa de retorno para pagamentos em dinheiro",
                },
            ]
        },
        methods: {
            changeState(state) {
                if (state === 0 || state === 1) {
                    this.state = state;
                }
            }
        },
        watch: {
        },
        mounted() {
            this.$nextTick(() => {
                if (this.$refs['supportPane']) {
                    this.$refs.supportPane.hidden = false;
                }
            })
        }
    });
}, Vue);