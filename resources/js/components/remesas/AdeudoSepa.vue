<template>
	<div>
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn
                        v-on="on"
                        color="white"
                        icon
                        @click="goRemesas"
                    >
                        <v-icon color="primary">airplay</v-icon>
                    </v-btn>
                </template>
                <span>Ir a Remesas</span>
            </v-tooltip>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm3 d-flex>
                            <v-select
                                v-model="cuenta_id"
                                :items="cuentas"
                                required
                                label="Cuenta"
                            ></v-select>
                        </v-flex>
                        <v-flex sm2>
                            <v-menu
                                v-model="menu2"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                min-width="290px"
                            >

                                <v-text-field
                                    slot="activator"
                                    :value="computedDateFormat"
                                    label="Fecha Remesa"
                                    prepend-icon="event"
                                    readonly
                                    data-vv-as="F. Remesa"
                                    ></v-text-field>
                                <v-date-picker
                                    v-model="fecha"
                                    no-title
                                    locale="es"
                                    first-day-of-week=1
                                    @input="menu2 = false"

                                ></v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-if="imp_remesa > 0"
                                v-model="imp_remesa"
                                label="Total Remesa"
                                readonly
                                type="number"
                                class="inputPrice"
                            >
                            </v-text-field>
                        </v-flex>

                        <v-flex sm1>
                            <v-text-field
                                v-if="imp_remesa > 0"
                                v-model="remesas"
                                label="Adeudos"
                                readonly
                                class="centered-input"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <div class="text-xs-center">
                                <v-btn @click="submit"  round :disabled="show_loading" :loading="show_loading" flat  color="primary">
                                    Generar Remesa
                                </v-btn>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-form>
        </v-card>
	</div>
</template>
<script>
    import moment from 'moment'
    import {mapGetters} from 'vuex';
    import Loading from '@/components/shared/Loading'
    // import MenuOpe from '@/components/shared/MenuGen'

	export default {
		$_veeValidate: {
      		validator: 'new'
    	},
        components: {
            'loading': Loading,
		},
    	data () {
      		return {
                titulo:"Generar fichero de Adeudos SEPA: recibos",
                fecha: new Date().toISOString().substr(0, 10),
                menu2: false,
                cuenta_id: 0,
                imp_remesa:0,
                remesas: 0,
                cuentas: [],
                show_loading: false
              }

        },
        mounted(){

            axios.get('/admin/sepa/index')
                .then(res => {

                    this.cuentas = res.data.cuentas;
                    this.cuenta_id = res.data.cuentas[0].value;
                    this.show_loading = false;
                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'dash' })
                })
        },
        computed: {
            ...mapGetters([
	    		'isRoot'
    		]),
            computedDateFormat() {
                moment.locale('es');
                return this.fecha ? moment(this.fecha).format('L') : '';
            }
        },
    	methods:{
            submit() {

                this.show_loading = true;

                var url = '/admin/sepa/recibos';

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios.post(url,
                            {
                                fecha: this.fecha,
                                cuenta_id: this.cuenta_id
                            })
                            .then(response => {

                                let blob = new Blob([response.data.xml], { type: 'application/xml' })
                                let link = document.createElement('a')
                                link.href = window.URL.createObjectURL(blob)
                                link.download = 'RECIB'+new Date().getFullYear()+(new Date().getMonth()+1)+(new Date().getDate())+'.XML';

                                document.body.appendChild(link);
                                link.click()
                                document.body.removeChild(link);

                                this.imp_remesa = response.data.importe.toFixed(2);
                                this.remesas = response.data.adeudos;

                                this.$toast.success("Se ha generado correctamente la remesa");

                                this.show_loading = false;
                                })
                                .catch(err => {
                                    
                                    this.$toast.error(err.response.data.message);
                                    this.show_loading = false;
                                })
                        }
                    else{
                        this.show_loading = false;
                    }
                });

            },
            clearDate(){
                this.fecha = null;
            },
            goRemesas(){
                this.$router.push({ name: 'remesa.index' })
            }
    }
  }
</script>

<style scoped>
.centered-input >>> input {
  text-align: center
}

.inputPrice >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}
</style>
