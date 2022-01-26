<template>
    <div>
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{ titulo }}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="documento.id" :cerrado="documento.cerrado" @show-upload="showUpload" @set-bloqueo="setBloqueo"> </menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm4 d-flex>
                            <v-select
                                :readonly="computedCerrado"
                                v-model="documento.archivo_id"
                                :items="archivos"
                                v-validate="'required'"
                                data-vv-name="archivo_id"
                                data-vv-as="Archivo"
                                :error-messages="errors.collect('archivo_id')"
                                @change="loadCarpeta(documento.archivo_id)"
                                label="Archivo"
                            ></v-select>
                        </v-flex>
                        <v-flex sm4 d-flex>
                            <v-select
                                :readonly="computedCerrado"
                                v-model="documento.carpeta_id"
                                v-validate="'required'"
                                :error-messages="errors.collect('carpeta_id')"
                                data-vv-name="carpeta_id"
                                data-vv-as="Carpeta"
                                :items="carpetas"
                                label="Carpeta"
                            ></v-select>
                        </v-flex>
                        <v-flex sm3>
                            <v-menu
                                :disabled="!hasDocumenta || computedCerrado"
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
                                    :disabled="!hasDocumenta || computedCerrado"
                                    slot="activator"
                                    :value="computedFecha"
                                    label="Fecha"
                                    append-icon="event"
                                    readonly
                                    class="center"
                                    data-vv-as="Fecha"
                                ></v-text-field>
                                <v-date-picker
                                    :disabled="!hasDocumenta"
                                    v-model="documento.fecha"
                                    no-title
                                    locale="es"
                                    first-day-of-week="1"
                                    @input="menu2 = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm10>
                            <v-text-field
                                :disabled="!hasDocumenta || computedCerrado"
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
                                :disabled="!hasDocumenta || computedCerrado"
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
                            <v-text-field :disabled="!hasDocumenta" v-model="computedFModFormat" label="Modificado" readonly>
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field :disabled="!hasDocumenta" v-model="computedFCreFormat" label="Creado" readonly> </v-text-field>
                        </v-flex>
                        <v-flex sm1></v-flex>
                        <v-flex sm2 v-show="hasDocumenta">
                            <div class="text-xs-center">
                                <v-btn @click="submit" :disabled="computedCerrado" round :loading="enviando" block color="primary">
                                    Guardar
                                </v-btn>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-form>
            <v-container v-show="extractos.length > 0">
                <v-data-table :headers="headers" :items="extractos" rows-per-page-text="Registros por página" class="elevation-1">
                    <template v-slot:items="props">
                        <td class="text-xs-left">{{ formatDate(props.item.fecha) }}</td>
                        <td class="text-xs-left">
                            {{ props.item.concepto }}
                            <p v-if="props.item.nota > ''">
                                <span class="font-italic black--text"
                                    ><span class="lime accent-2">{{ props.item.nota }}</span></span
                                >
                            </p>
                        </td>
                        <td class="text-xs-right">
                            {{
                                props.item.importe
                                    | currency('€', 2, {
                                        thousandsSeparator: '.',
                                        thousandsSeparator: '.',
                                        decimalSeparator: ',',
                                        symbolOnLeft: false,
                                    })
                            }}
                        </td>
                    </template>
                    <template slot="pageText" slot-scope="props">
                        Registros {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                    </template>
                </v-data-table>
            </v-container>
            <v-container v-show="files.length > 0">
                <v-layout row wrap>
                    <v-flex sm2 v-for="f in files" :key="f.url">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn icon @click="download(f.id)" v-on="on">
                                    <div v-html="extensionFile(f.url)"></div>
                                </v-btn>
                            </template>
                            <span>Abrir Documento</span>
                        </v-tooltip>

                        <v-icon v-if="!computedCerrado" color="red accent-4" @click="destroyFile(f.id)">clear</v-icon>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-container v-show="show_upload && !computedCerrado && hasDocumenta">
                <v-layout row wrap>
                    <v-flex sm12>
                        <vue-dropzone
                            ref="myVueDropzone"
                            id="dropzone"
                            :options="dropzoneOptions"
                            @vdropzone-success="upload"
                            @vdropzone-error="verror"
                        ></vue-dropzone>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card>
    </div>
</template>
<script>
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
import moment from 'moment';
import MenuOpe from './MenuOpe';
import vue2Dropzone from 'vue2-dropzone';
import MyDialog from '@/components/shared/MyDialog';
import Loading from '@/components/shared/Loading';
import { mapGetters } from 'vuex';

export default {
    $_veeValidate: {
        validator: 'new',
    },
    components: {
        loading: Loading,
        'my-dialog': MyDialog,
        vueDropzone: vue2Dropzone,
        'menu-ope': MenuOpe,
    },
    data() {
        return {
            titulo: '* Documentos',
            documento: {
                id: 0,
                empresa_id: '',
                archivo_id: '',
                concepto: '',
                fecha: '',
                cerrado: 0,
                confidencial: false,
                username: '',
                updated_at: '',
                created_at: '',
            },

            headers: [
                { text: 'Fecha', value: 'fecha' },
                { text: 'Concepto extracto', value: 'nombre' },
                { text: 'Importe', value: 'importe' },
                // { text: 'Acciones', align: 'Center'}
            ],

            archivos: [],
            carpetas: [],
            extractos: [],
            files: [],
            nf: 0,

            documento_id: '',

            menu2: false,
            status: false,
            enviando: false,
            show: true,
            show_upload: false,
            show_loading: true,

            dropzoneOptions: {
                url: '/mto/filedocs/' + this.$route.params.id,
                //     acceptedFiles: 'image/*,.pdf,.rar,.zip,.txt,.n43,.docx,.xlsx,application/x-msdownload',
                paramName: 'files',
                thumbnailWidth: 150,
                maxFiles: 10,
                headers: {
                    'X-CSRF-TOKEN': window.axios.defaults.headers.common['X-CSRF-TOKEN'],
                },
                dictDefaultMessage: 'Arrastra aquí tus documentos para subir al servidor',
            },
        };
    },
    mounted() {
        var id = this.$route.params.id;
        //console.log(this.$route.params);
        if (id > 0)
            axios
                .get('/mto/documentos/' + id + '/edit')
                .then((res) => {
                    this.documento = res.data.documento;
                    // console.log(this.documento);

                    if (this.documento.confidencial && !this.isAdmin) {
                        this.$toast.error('Documento confidencial, contactar administrador!');
                        this.$router.push({ name: 'documento.index' });
                    }

                    this.extractos = res.data.extractos;
                    this.archivos = res.data.archivos;
                    this.carpetas = res.data.carpetas;
                    this.files = res.data.files;

                    if (this.files.length == 0) this.show_upload = true;

                    this.show = true;
                    this.show_loading = false;
                })
                .catch((err) => {
                    if (err.response.status == 404) this.$toast.error('Documento No encontrado!');
                    else this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'documento.index' });
                });
    },
    computed: {
        ...mapGetters(['hasDocumenta', 'isAdmin']),
        computedCerrado() {
            if (this.documento.cerrado) return true;
            else return false;
        },
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
        computedOrdenFile() {
            this.nf = this.nf + 1;

            return this.nf;
        },
    },
    methods: {
        extensionFile(url) {
            const a = url.split('.');

            switch (a[a.length - 1]) {
                case 'pdf':
                    var icono = 'pdf.png';
                    break;
                case 'jpg':
                    var icono = 'img.png';
                    break;
                case 'doc':
                    var icono = 'word.png';
                    break;
                case 'docx':
                    var icono = 'word.png';
                    break;
                case 'xls':
                    var icono = 'excel.png';
                    break;
                case 'xlsx':
                    var icono = 'excel.png';
                    break;
                case 'rar':
                    var icono = 'rar.png';
                    break;
                case 'zip':
                    var icono = 'zip.png';
                    break;
                default:
                    var icono = 'document.png';
                    break;
            }

            return '<img src="/assets/' + icono + '" alt="pdf" height="50px">';
        },
        download(file_id) {
            var url = '/mto/filedocs/' + file_id;
            window.open(url, '_blank');
            // return;

            // axios.get('/mto/filedocs/'+file_id)
            //     .then(res => {
            //         let blob = new Blob([res.data], { type: 'application/pdf' })
            //         let link = document.createElement('a')
            //         link.href = window.URL.createObjectURL(blob)
            //         link.download = 'REM'+new Date().getFullYear()+'-'+(new Date().getMonth()+1)+'.pdf';
            //         link.click()
            //     })
            //     .catch(err => {

            //         this.$toast.error('Error al obtener documento!');
            //     })
        },
        showUpload(e) {
            this.show_upload = e;
        },
        setBloqueo(e) {
            this.documento.cerrado = e;
            this.submit();
        },
        formatDate(f) {
            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        submit() {
            this.enviando = true;

            var url = '/mto/documentos/' + this.documento.id;

            this.$validator.validateAll().then((result) => {
                if (result) {
                    axios
                        .put(url, this.documento)
                        .then((response) => {
                            // console.log(response);
                            this.$toast.success(response.data.message);
                            this.documento = response.data.documento;
                            this.enviando = false;
                        })
                        .catch((err) => {
                            //console.log(err.response.data.errors);
                            if (err.request.status == 422) {
                                // fallo de validated.
                                const msg_valid = err.response.data.errors;
                                for (const prop in msg_valid) {
                                    // this.$toast.error(`${msg_valid[prop]}`);
                                    //console.log(prop);
                                    this.errors.add({
                                        field: prop,
                                        msg: `${msg_valid[prop]}`,
                                    });
                                }
                            } else {
                                this.$toast.error(err.response.data.message);
                            }
                            this.enviando = false;
                        });
                } else {
                    this.enviando = false;
                }
            });
        },
        upload(file, response) {
            this.files = response.files;

            this.$toast.success('Ficheros subidos correctamente!');

            this.show_upload = false;
        },
        verror(file, err) {
            const m = err.errors.files[0];
            this.$toast.error(m);
        },
        destroyFile(id) {
            axios
                .post('/mto/filedocs/' + id, { _method: 'delete' })
                .then((response) => {
                    this.$toast.success('Adjunto eliminado!');
                    this.files = response.data.files;
                })
                .catch((err) => {
                    this.status = true;
                    var msg = err.response.data.message;
                    this.$toast.error(msg);
                });
        },
        loadCarpeta(archivo_id) {
            axios
                .get('/mto/carpetas/' + archivo_id)
                .then((res) => {
                    this.carpetas = res.data.carpetas;
                    if (this.carpetas.length == 1) this.documento.carpeta_id = this.carpetas[0].id;
                    else this.documento.carpeta_id = '';
                })
                .catch((err) => {
                    if (err.response.status == 404) this.$toast.error('Error al recargar carpetas!');
                    else this.$toast.error(err.response.data.message);
                });
        },
    },
};
</script>
<style scope>
.v-text-field {
    padding-top: 2px;
    margin-top: 4px;
}
.theme--light.v-input--is-disabled,
.theme--light.v-input--is-disabled input,
.theme--light.v-input--is-disabled textarea {
    color: #263238;
}

.v-form > .container > .layout > .flex {
    padding: 0px 8px;
}

.inputPrice >>> input {
    text-align: center;
    -moz-appearance: textfield;
}

input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}
</style>
