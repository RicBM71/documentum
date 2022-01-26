
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
                            :items="this.carpetas"
                            :search="search"
                            @update:pagination="updateEventPagina"
                            :pagination.sync="pagination"
                            rows-per-page-text="Registros por página"
                            >
                                <template slot="items" slot-scope="props">
                                    <td :class="props.item.archivo.color">{{ props.item.archivo.nombre }}</td>
                                    <td :class="props.item.color"><span :class="tachado(props.item.activa)">{{ props.item.nombre }}</span></td>
                                    <td :class="props.item.color"><span v-show="hasDocumenta">{{ props.item.path }}</span></td>
                                    <td :class="props.item.color"><span v-show="hasDocumenta">{{ props.item.color }}</span></td>
                                    <td class="justify-center layout px-0">
                                        <v-icon
                                            small
                                            class="mr-2"
                                            @click="editItem(props.item.id)"
                                        >
                                            edit
                                        </v-icon>


                                        <v-icon v-if="isAdmin"
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
import {mapActions} from "vuex";
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        titulo: "Carpeta",
        paginaActual:{},
        pagination:{
            model: "carpeta",
            descending: false,
            page: 1,
            rowsPerPage: 10,
            sortBy: "id",
        },
        search:"",
        headers: [
            {
                text: 'Archivo',
                align: 'left',
                value: 'archivo.nombre'
            },
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
        carpetas:[],
        status: false,
		registros: false,
        dialog: false,
        carpeta_id: 0,
        show_loading: true

      }
    },
    mounted()
    {

        if (this.getPagination.model == this.pagination.model)
            this.updatePosPagina(this.getPagination);
        else
            this.unsetPagination();

        axios.get('/mto/carpetas')
            .then(res => {

                this.carpetas = res.data;
                this.registros = true;
                this.show_loading = false;
            })
            .catch(err =>{
               // console.log(err);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    computed: {
        ...mapGetters([
            'isAdmin',
            'hasDocumenta',
            'getPagination'
        ])
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
            this.$router.push({ name: 'carpeta.create'})
        },
        editItem (id) {
            this.setPagination(this.paginaActual);
            this.$router.push({ name: 'carpeta.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.carpeta_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/mto/carpetas/'+this.carpeta_id,{_method: 'delete'})
                .then(response => {

                if (response.status == 200){
                    this.$toast.success('Carpeta eliminada!');
                    this.carpetas = response.data;
                }
                })
            .catch(err => {
                this.status = true;
               // console.log(err.response.data.message);
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },
        tachado(activa){

            if (!activa) return 'tachado';
            return null;
        }
    }
  }
</script>

<style scope>
  .tachado{text-decoration:line-through;}
</style>
