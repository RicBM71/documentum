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
                        <v-flex sm12>
                            <vue-dropzone
                                ref="myVueDropzone"
                                id="dropzone"
                                :options="dropzoneOptions"
                                @vdropzone-success="upload"
                                @vdropzone-error="vderror"
                            ></vue-dropzone>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-form>
        </v-card>
	</div>
</template>
<script>
    import {mapGetters} from 'vuex';
    import Loading from '@/components/shared/Loading'
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

	export default {
		$_veeValidate: {
      		validator: 'new'
    	},
        components: {
            'loading': Loading,
            'vueDropzone': vue2Dropzone,
		},
    	data () {
      		return {

                titulo:   "Importar Norma 43",
                destino: "",
                disabled: true,
                show_loading: false,

                dropzone:{},
                dropzoneOptions: {
                    url: '/mto/extractos/banco',
                    paramName: 'files',
                    acceptedFiles: '.n43',
                    maxFiles: 1,
                    maxFilesize: 0.5,
                    createImageThumbnails: false,
                    headers: {
		    		    'X-CSRF-TOKEN':  window.axios.defaults.headers.common['X-CSRF-TOKEN']
                    },
                    dictDefaultMessage: 'Arrastra aquí el documento para subirlo'
                }
              }

        },
        mounted(){
            if (!this.hasDocumenta){
                this.$toast.error('No tiene permiso para subir documentos');
                this.$router.push({ name: 'dash'})
            }
        },
        computed: {
            ...mapGetters([
                'hasDocumenta',
            ]),
            computedDisabled(){
                if (this.destino != '')
                    return false;
                return false;
            }
        },
    	methods:{
            upload(file, response){

                this.show_loading = true;
                this.mostrar = false;
                this.$toast.success(response);

                this.$router.push({ name: 'extracto.index'})

            },
            vderror(file){
                this.$toast.error('El fichero no es correcto o la cuenta no existe');
                this.$router.push({ name: 'dash' })
            }
    }
  }
</script>
