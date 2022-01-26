<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <v-container v-if="registros">
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
                            @update:pagination="updateEventPagina"
                            :pagination.sync="pagination"
                            :items="this.archivos"
                            rows-per-page-text="Registros por página"
                            >
                                <template slot="items" slot-scope="props">
                                    <td>{{ props.item.nombre }}</td>
                                    <td>{{ props.item.path }}</td>
                                    <td :class="props.item.color">{{ props.item.color }}</td>
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
        </v-container>
    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex'
import {mapState} from 'vuex'
import {mapActions} from "vuex";

  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        titulo: "Archivo",
        paginaActual:{},
        pagination:{
            model: "archivo",
            descending: false,
            page: 1,
            rowsPerPage: 5,
            sortBy: "id",
        },
        headers: [
          {
            text: 'Nombre',
            align: 'left',
            value: 'nombre'
          },
          {
            text: 'Path',
            align: 'left',
            value: 'path'
          },
          {
            text: 'Color',
            align: 'Left',
            value: 'color'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        archivos:[],
        status: false,
		registros: false,
        dialog: false,
        archivo_id: 0,
        show_loading: true
      }
    },
    mounted()
    {
        if (this.getPagination.model == this.pagination.model)
            this.updatePosPagina(this.getPagination);
        else
            this.unsetPagination();

        axios.get('/admin/archivos')
            .then(res => {
                this.archivos = res.data;
                this.registros = true;
                this.show_loading = false;
            })
            .catch(err =>{
                //console.log(err.response);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    computed: {
        ...mapGetters([
            'getPagination'
        ]),
    },
    methods:{
        ...mapActions([
            'setPagination',
            'unsetPagination'
		]),
        updateEventPagina(obj){
            this.paginaActual = obj;
        },
        updatePosPagina(pag){

            this.pagination.page = pag.page;
            this.pagination.descending = pag.descending;
            this.pagination.rowsPerPage= pag.rowsPerPage;
            this.pagination.sortBy = pag.sortBy;

        },
        create(){
            this.$router.push({ name: 'archivo.create'})
        },
        editItem (id) {
            this.setPagination(this.paginaActual);
            this.$router.push({ name: 'archivo.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.archivo_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/archivos/'+this.archivo_id,{_method: 'delete'})
                .then(response => {

                if (response.status == 200){
                    this.$toast.success('Archivo eliminado!');
                    this.archivos = response.data;
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
