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
                        :items="empresas"
                        rows-per-page-text="Registros por página"
                        >
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.id }}</td>
                                <td>{{ props.item.nombre }}</td>
                                <td>{{ props.item.cif }}</td>
                                <td>{{ props.item.contacto }}</td>
                                <td>{{ props.item.telefono1 }}</td>
                                <td class="justify-center layout px-0">
                                    <v-icon
                                        v-show="props.item.id==empresaActiva"
                                        small
                                        class="mr-2"
                                        @click="editItem(props.item.id)"
                                    >
                                        edit
                                    </v-icon>


                                    <v-icon
                                        v-show="props.item.id!=empresaActiva"
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
import {mapGetters} from 'vuex';
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
    },
    data () {
      return {
        titulo: "Empresas",
        headers: [
          {
            text: 'ID',
            align: 'center',
            value: 'id'
          },
          {
            text: 'Nombre',
            align: 'left',
            value: 'nombre'
          },
          {
            text: 'CIF',
            align: 'left',
            value: 'cif'
          },
          {
            text: 'Contacto',
            align: 'left',
            value: 'contacto'
          },
          {
            text: 'Teléfono',
            align: 'Left',
            value: 'telefono1'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        empresas:[],
        status: false,
		registros: false,
        dialog: false,
        empresa_id: 0,
        titulo:"Empresas"
      }
    },
    mounted()
    {

        axios.get('/admin/empresas')
            .then(res => {

                this.empresas = res.data;
                this.registros = true;
            })
            .catch(err =>{
                //console.log(err.response);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    computed: {
        ...mapGetters([
            'empresaActiva',
        ]),
    },
    methods:{
        create(){
            this.$router.push({ name: 'empresa.create'})
        },
        editItem (id) {
            this.$router.push({ name: 'empresa.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.empresa_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/empresa/'+this.id,{_method: 'delete'})
                .then(response => {
                this.empresas = response.data;
                this.$toast.success('Empresa eliminada!');

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
