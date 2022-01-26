<template>
	<div>
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <!-- <menu-ope :id="user.id"></menu-ope> -->
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm3>
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
                                    clearable
                                    label="Fecha Remesa"
                                    prepend-icon="event"
                                    readonly
                                    data-vv-as="F. Remesa"
                                    @click:clear="clearDate"
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
                    </v-layout>
                    <v-layout>
                        <v-flex sm4></v-flex>
                        <v-flex sm4>
                            <div class="text-xs-center">
                                <v-btn @click="printPDF"  round  :loading="show_loading" block  color="primary">
                                    Imprimir Facturas
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
                titulo:   "Imprimir Facturas",
                fecha: new Date().toISOString().substr(0, 10),
                disabled: true,
                menu2: false,
                imp_remesa:0,
                adeudos: 0,
                show_loading: false
              }

        },
        mounted(){
        },
        computed: {
            ...mapGetters([
	    		'isAdmin'
    		]),
            computedDateFormat() {
                moment.locale('es');
                return this.fecha ? moment(this.fecha).format('L') : '';
            }
        },
    	methods:{
            printPDF() {

                var url = '/ventas/facturas/'+this.fecha+'/print';
                window.open(url, '_blank');

            },
            clearDate(){
                this.fecha = null;
            }
    }
  }
</script>
