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
                    <v-flex xs6></v-flex>
                    <v-flex xs6>
                        <v-spacer></v-spacer>
                        <v-text-field
                            v-model="search"
                            append-icon="search"
                            label="Buscar"
                            single-line
                            hide-details
                        ></v-text-field>
                    </v-flex>
                </v-layout>
                <br/>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-data-table
                        :headers="headers"
                        :items="productos"
                        :search="search"
                        rows-per-page-text="Registros por página"
                        >
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.nombre }}</td>
                                <td>{{ props.item.iva.nombre }}</td>
                                <td>{{ props.item.retencion.nombre }}</td>
                                <td class="text-xs-right">{{ props.item.importe| currency('€', 2, { thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false }) }}</td>
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
        titulo:"Productos",
        search:"",
        headers: [
          {
            text: 'Nombre',
            align: 'left',
            value: 'nombre'
          },
          {
            text: 'IVA',
            align: 'left',
            value: 'iva'
          },
          {
              text: 'Retención',
            align: 'Left',
            value: 'retencion'
          },
          {
          text: 'Importe',
          align: 'center',
          value: 'importe'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        productos:[],
        status: false,
		registros: false,
        dialog: false,
        producto_id: 0,

      }
    },
    mounted()
    {

        axios.get('/mto/productos')
            .then(res => {
               // console.log(res);
                this.productos = res.data;
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
            this.$router.push({ name: 'producto.create'})
        },
        editItem (id) {
            this.$router.push({ name: 'producto.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.producto_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/mto/productos/'+this.producto_id,{_method: 'delete'})
                .then(response => {

                this.productos = response.data;
                this.$toast.success('Producto eliminado!');


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
