<template>
    <div>
        <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <v-card>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-data-table
                    :headers="headers"
                    :items="lineas"
                    rows-per-page-text="Registros por página"
                    >
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.producto.nombre }}</td>
                            <td>{{ props.item.nombre }}</td>
                            <td class="text-xs-right">{{ props.item.unidades | currency('', 0, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
                            <td class="text-xs-right">{{ props.item.impuni | currency('€', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
                            <td class="text-xs-right">{{ props.item.poriva | currency('%', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
                            <td class="text-xs-right">{{ props.item.porirpf| currency('%', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
                            <td class="text-xs-right">{{ props.item.dto| currency('%', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
                            <td class="text-xs-right">{{ props.item.importe| currency('€', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
                            <td class="justify-center layout px-0">
                                <v-icon  v-if="eje_fac==0"
                                    small
                                    class="mr-2"
                                    @click="editItem(props.item.id)"
                                >
                                    edit
                                </v-icon>


                                <v-icon  v-if="eje_fac==0"
                                small
                                @click="openDialog(props.item.id)"
                                >
                                delete
                                </v-icon>
                            </td>
                        </template>
                        <template slot="footer">
                        <td class="text-xs-right font-weight-bold" colspan="2">TOTAL</td>
                        <td class="text-xs-right font-weight-bold">{{ totales.unidades| currency(' ', 0, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
                        <td class="text-xs-right font-weight-bold"></td>
                        <td class="text-xs-right font-weight-bold">{{ totales.impiva| currency('€', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
                        <td class="text-xs-right font-weight-bold">{{ totales.impirpf| currency('€', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
                        <td></td>
                        <td class="text-xs-right font-weight-bold">{{ computedTotAlb | currency('€', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
                        <td class="">
                        </td>
                        </template>
                        <template slot="pageText" slot-scope="props">
                            Registros {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                        </template>
                    </v-data-table>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-flex xs3>
                    <v-btn round flat v-if="eje_fac==0" color="primary" v-on:click="create" small >
                        <v-icon small>add</v-icon> Crear Línea
                    </v-btn>
                </v-flex>
            </v-layout>
        </v-card>
        <albalin-create :albaran_id="albaran_id" :dialog_lin.sync="dialog_lin" @reLoadLineas="reLoadLineas"></albalin-create>
        <albalin-edit :albalin_id="albalin_id" :dialog_edt.sync="dialog_edt" :albalin="albalin" @reLoadLineas="reLoadLineas"></albalin-edit>
    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
import AlbalinCreate from './AlbalinCreate'
import AlbalinEdit from './AlbalinEdit'
export default {
    props:{
        albaran_id: Number,
        eje_fac: Number
    },
    components: {
        'my-dialog': MyDialog,
        'albalin-create': AlbalinCreate,
        'albalin-edit': AlbalinEdit
	},
    data () {
        return {
            dialog: false,
            lineas: [],
            totales:"",
            albalin: {},
            albalin_id:0,
            dialog_lin: false,
            dialog_edt: false,
           headers: [
          {
            text: 'Producto',
            align: 'left',
            value: 'producto.nombre',
            sortable: false
          },
          {
            text: 'Nombre',
            align: 'left',
            value: 'nombre',
            sortable: false
          },
          {
            text: 'Unidades',
            align: 'center',
            value: 'unidades',
            sortable: false
          },
          {
            text: 'Imp. Uni',
            align: 'center',
            value: 'impuni',
            sortable: false
          },
          {
            text: 'IVA',
            align: 'center',
            value: 'poriva',
            sortable: false
          },
          {
            text: 'IRPF',
            align: 'center',
            value: 'porirpf',
            sortable: false
          },
          {
            text: 'DTO',
            align: 'center',
            value: 'dto',
            sortable: false
          },
          {
            text: 'Importe',
            align: 'center',
            value: 'importe',
            sortable: false
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: '',
            sortable: false
          }
        ],
        }
    },
    mounted(){

        axios.get('/ventas/albalins/'+this.albaran_id)
        .then(res => {

            this.lineas = res.data.lineas;
            this.totales = res.data.totales;

        })
        .catch(err => {
            if (err.response.status == 404)
                this.$toast.error("Albarán No encontrado!");
            else
                this.$toast.error(err.response.data.message);
            this.$router.push({ name: 'albaran.index'})
        })
    },
    computed:{
        computedTotAlb(){

            return parseFloat(this.totales.importe) - parseFloat(this.totales.impirpf)  + parseFloat(this.totales.impiva);
        }
    },
    methods:{
        reLoadLineas(lineas, totales){
            this.lineas = lineas;
            this.totales = totales;
        },
        create(){
            this.dialog_lin = true;
        },
        editItem(id){
            axios.get('/ventas/albalins/'+id+"/edit")
                .then(res => {
                this.albalin = res.data.albalin;
            });

            this.albalin_id = id;
            this.dialog_edt = true;
        },
        openDialog (id){
            this.dialog = true;
            this.albalin_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/ventas/albalins/'+this.albalin_id,{_method: 'delete'})
                .then(res => {
                this.lineas = res.data.lineas;
                this.totales = res.data.totales;

            })
            .catch(err => {
                this.status = true;
                var msg = err.response.data;
                this.$toast.error(msg);

            });

        },
    }

}
</script>

