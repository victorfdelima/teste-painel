$(function () {
    console.log('Vue loaded');
    const requestServiceVueElement = new Vue({
        el: '#vue__application',
        name: 'RequestService',
        data: {
            stops: [],
        },
        watch: {
            stops: {
                deep: true,
                handler(n) {
                    if (n.length) {
                        this.setAutoCompleteOnField(n.length)
                    }
                }
            }
        },
        methods: {
            addStop() {
                if (this.stops.length < 5) {
                    this.stops.push(this.stops.length + 1);
                }
            },
            removeStop(index) {
                if (this.stops.length > 0) {
                    this.stops.splice(index, 1);
                }
            },
            setAutoCompleteOnField(index) {
                setTimeout(() => {
                    adicionarAutoComplete(
                        `parada-input-${index}`,
                        document.googleMapsInstance,
                        `PARA${index}`,
                        `pp_lat_${index}`,
                        `pp_long_${index}`
                    );
                }, 1000);
            }
        },
        mounted() {
            this.$nextTick(() => {
                if (this.$refs['stopsForm']) {
                    this.$refs.stopsForm.hidden = false;
                }
                adicionarAutoComplete('origin-input', document.googleMapsInstance, 'ORIG', 'origin_latitude', 'origin_longitude');
                adicionarAutoComplete('destination-input', document.googleMapsInstance, 'DEST', 'destination_latitude', 'destination_longitude');
            })
        }
    })
}, Vue);