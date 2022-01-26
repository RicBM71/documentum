<template>
    <div v-if="registros">
        <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-container>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-data-table
                        :headers="headers"
                        :items="this.contadores"
                        rows-per-page-text="Registros por página"
                        >
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.ejercicio }}</td>
                                <td class="text-xs-center">{{ props.item.seriealb+"-"+props.item.albaran}}</td>
                                <td class="text-xs-center">{{ props.item.seriefac+"-"+props.item.factura}}</td>
                                <td class="text-xs-center">{{ props.item.serieabo+"-"+props.item.abono}}</td>
                                <td class="justify-center layout px-0">
                                    <v-icon
                                        small
                                        class="mr-2"
                                        @click="editItem(props.item.id)"
                                    >
                                        edit
                                    </v-icon>


                                    <v-icon
                                    small
                                    @click="openDialog(props.item.id)"
                                    >
                                    delete
                                    </v-icon>
                                </td>
                            </template>
                            <template slot="pageText" slot-scope="props">
                                Registros {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card>
    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
import MenuOpe from './MenuOpe'
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
    },
    data () {
      return {
        titulo:"Contadores",
        headers: [
          {
            text: 'Ejercicio',
            align: 'left',
            value: 'ejercicio'
          },
          {
            text: 'Serie Albarán',
            align: 'center',
            value: 'serie'
          },
          {
            text: 'Serie Facturas',
            align: 'center',
            value: 'serie'
          },
          {
            text: 'Serie Abonos',
            align: 'center',
            value: 'serie'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        contadores:[],
        status: false,
		registros: false,
        dialog: false,
        contador_id: 0,
        titulo:"Contadores"
      }
    },
    mounted()
    {

        axios.get('/admin/contadors')
            .then(res => {

                this.contadores = res.data;
                this.registros = true;
            })
            .catch(err =>{
                //console.log(err.response);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    methods:{
        create(){
            this.$router.push({ name: 'contador.create'})
        },
        editItem (id) {
            this.$router.push({ name: 'contador.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.iva_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/contadores/'+this.iva_id,{_method: 'delete'})
                .then(response => {

                if (response.status == 200){
                    this.$toast.success('Contador eliminado!');
                    this.contadores = response.data;
                }
                })
            .catch(err => {
                this.status = true;
               // console.log(err.response.data.message);
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },

    }
  }
</script>
