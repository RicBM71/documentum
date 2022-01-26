<template>
	<div>
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope
                    :id="documento.id"
                    :cerrado="documento.cerrado">
                </menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm4 d-flex>
                            <v-text-field
                                :disabled="!hasDocumenta"
                                :value="archivo_nombre"
                                label="Archivo"
                                >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm4 d-flex>
                            <v-text-field
                                :disabled="!hasDocumenta"
                                :value="carpeta_nombre"
                                label="Carpeta"
                                >
                            </v-text-field>
                        </v-flex>
                         <v-flex sm3>
                            <v-text-field
                                :disabled="!hasDocumenta"
                                :value="computedFecha"
                                label="Fecha"
                                class="center"
                                data-vv-as="Fecha"
                                ></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm10>
                            <v-text-field
                                :disabled="!hasDocumenta"
                                v-model="documento.concepto"
                                v-validate="'required'"
                                :error-messages="errors.collect('concepto')"
                                label="Concepto"
                                data-vv-name="concepto"
                                data-vv-as="concepto"
                                required
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-switch
                                v-show="hasDocumenta"
                                :disabled="!hasDocumenta"
                                v-model="documento.confidencial"
                                color="error"
                                label="Confidencial"
                            ></v-switch>
                        </v-flex>

                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm3>
                            <v-text-field
                                :disabled="!hasDocumenta"
                                v-model="documento.username"
                                :error-messages="errors.collect('username')"
                                label="Usuario"
                                data-vv-name="username"
                                readonly
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                :disabled="!hasDocumenta"
                                v-model="computedFModFormat"
                                label="Modificado"
                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                :disabled="!hasDocumenta"
                                v-model="computedFCreFormat"
                                label="Creado"
                                readonly
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm1></v-flex>
                    </v-layout>
                </v-container>
            </v-form>
            <v-container v-show="extractos.length > 0">
                <v-data-table
                    hide-actions
                    :headers="headers"
                    :items="extractos"
                    class="elevation-1"
                >
                    <template v-slot:items="props">
                        <td class="text-xs-left">{{ formatDate(props.item.fecha) }}</td>
                        <td class="text-xs-left">{{ props.item.concepto }}
                        <p v-if="props.item.nota>''"><span class='font-italic black--text'><span class="lime accent-2">{{ props.item.nota }}</span></span></p></td>
                        <td class="text-xs-right">{{ props.item.importe | currency('€', 2, { thousandsSeparator:'.', thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false })}}</td>
                    </template>
                </v-data-table>
            </v-container>
            <v-container v-show="files.length > 0">
                <v-layout row wrap>
                    <v-flex sm2
                        v-for="f in files"
                        :key="f.url">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    icon
                                    @click="download(f.id)"
                                    v-on="on"
                                >

                                    <div v-html="extensionFile(f.url)"></div>
                                </v-btn>
                            </template>
                            <span>Abrir Documento</span>
                        </v-tooltip>

                    </v-flex>
                </v-layout>
            </v-container>
        </v-card>
	</div>
</template>
<script>
import moment from 'moment'
import MenuOpe from './MenuOpe'
import Loading from '@/components/shared/Loading'
import {mapGetters} from 'vuex';

	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'loading': Loading,
            'menu-ope': MenuOpe,
		},
    	data () {
      		return {
                titulo:"Documentos",
                documento: {
                    id:0,
                    empresa_id:"",
                    archivo_id:"",
                    concepto:"",
                    fecha:"",
                    cerrado: 0,
                    confidencial:false,
                    username: "",
                    updated_at:"",
                    created_at:"",
                },

                 headers: [
                    { text: 'Fecha', value: 'fecha' },
                    { text: 'Concepto extracto', value: 'nombre' },
                    { text: 'Importe', value: 'importe' },
                ],

                extractos:[],
                files: [],

                documento_id: "",

                menu2: false,
        		status: false,
                enviando: false,
                show: true,
                show_upload: false,
                show_loading: true,

                archivo_nombre:"",
                carpeta_nombre:""


      		}
        },
        mounted(){

            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/mto/documentos/'+id)
                    .then(res => {

                        this.documento = res.data.documento;

                        if (this.documento.confidencial && !this.isAdmin){
                            this.$toast.error("Documento confidencial, contactar administrador!");
                            this.$router.push({ name: 'documento.index'})
                        }

                        this.extractos = res.data.extractos;
                        this.files = res.data.files;

                        this.archivo_nombre = this.documento.archivo.nombre;
                        this.carpeta_nombre = this.documento.carpeta.nombre;

                        if (this.files.length == 0)
                            this.show_upload = true;

                        this.show=true;
                        this.show_loading = false;
                    })
                    .catch(err => {

                        if (err.response.status == 404)
                            this.$toast.error("Documento No encontrado!");
                        else
                            this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'documento.index'})
                    })
        },
        computed: {
            ...mapGetters([
                'hasDocumenta',
                'isAdmin'
		    ]),
            computedFecha() {
                moment.locale('es');
                return this.documento.fecha ? moment(this.documento.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.documento.updated_at ? moment(this.documento.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.documento.created_at ? moment(this.documento.created_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedOrdenFile(){
                this.nf = this.nf + 1;

                return this.nf;
            }

        },
    	methods:{
            extensionFile(url){

                const a = url.split('.');

                switch (a[a.length - 1]){
                    case 'pdf':
                        var icono = 'pdf.png';
                        break;
                    case 'jpg':
                        var icono = 'img.png';
                        break;
                    case 'doc':
                        var icono = 'word.png';
                        break
                    case 'docx':
                        var icono = 'word.png';
                        break
                    case 'xls':
                        var icono = 'excel.png';
                        break
                    case 'xlsx':
                        var icono = 'excel.png';
                        break
                    case 'rar':
                        var icono = 'rar.png';
                        break
                    case 'zip':
                        var icono = 'zip.png';
                        break
                    default:
                        var icono = 'document.png';
                        break
                }

                return '<img src="/assets/'+icono+'" alt="pdf" height="50px">';

            },
            download(file_id){

                var url = '/mto/filedocs/'+file_id;
                window.open(url, '_blank');
                // return;

                // axios.get('/mto/filedocs/'+file_id)
                //     .then(res => {
                //         let blob = new Blob([res.data], { type: 'application/pdf' })
                //         let link = document.createElement('a')
                //         link.href = window.URL.createObjectURL(blob)
                //         link.download = 'REM'+new Date().getFullYear()+'-'+(new Date().getMonth()+1)+'.pdf';
                //         link.click()

                //         //console.log(res);
                //     })
                //     .catch(err => {

                //         this.$toast.error('Error al obtener documento!');
                //     })

                //var url = '/mto/documentos/'+file_id+'/print';

                //window.open(url, '_blank');

            },
            showUpload(e){
                this.show_upload = e;
            },
            setBloqueo(e){
                this.documento.cerrado = e;
                this.submit();
            },
            formatDate(f){
                if (f == null) return null;
                    moment.locale('es');
                return moment(f).format('DD/MM/YYYY');
            },
    }
  }
</script>
<style scope>
.v-text-field {
    padding-top: 2px;
    margin-top: 4px;
}
.theme--light.v-input--is-disabled,  .theme--light.v-input--is-disabled input, .theme--light.v-input--is-disabled textarea {
    color: #263238;
}

.v-form>.container>.layout>.flex{
    padding: 0px 8px;
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
