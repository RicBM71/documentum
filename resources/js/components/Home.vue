<template>
<div>
	  <v-app light>
	    <v-toolbar class="white">
	      <v-toolbar-title>Sanaval Tecnología</v-toolbar-title>
	      <v-spacer></v-spacer>
            <v-btn v-if="!isLoggedIn" icon v-on:click="login">
                <v-icon>person</v-icon>
            </v-btn>
            <v-btn v-else icon :to="{name: 'dash'}">
                <v-icon>desktop_windows</v-icon>
            </v-btn>

	    </v-toolbar>
	    <v-content>
	      <section>
	        <v-parallax src="assets/hero.jpeg" height="600">
	          <v-layout
	            column
	            align-center
	            justify-center
	            class="white--text"
	          >

	            <h1 class="white--text mb-2 display-1 text-xs-center">Sanaval Tecnología</h1>
	            <div class="subheading mb-3 text-xs-center">Desarrollamos tu aplicación Web</div>
	          </v-layout>
	        </v-parallax>
	      </section>

	      <section>
	        <v-layout
	          column
	          wrap
	          class="my-5"
	          align-center
	        >
	          <v-flex xs12 sm4 class="my-3">
	            <div class="text-xs-center">
	              <h2 class="headline">Utilizamos la últimas Tecnologías</h2>
	              <span class="subheading">
	                Laravel 5 + Vue JS + MySql
	              </span>
	            </div>
	          </v-flex>
	          <v-flex xs12>
	            <v-container grid-list-xl>
	              <v-layout row wrap align-center>
	                <v-flex xs12 md4>
	                  <v-card class="elevation-0 transparent">
	                    <v-card-text class="text-xs-center">
	                      <v-icon x-large class="blue--text text--lighten-2">color_lens</v-icon>
	                    </v-card-text>
	                    <v-card-title primary-title class="layout justify-center">
	                      <div class="headline text-xs-center">Análisis funcional</div>
	                    </v-card-title>
	                    <v-card-text>
	                      Cuéntanos tu proyecto y buscaremos la solución más adecuada, nos comprometemos
                          con el proyecto de principio a fin, ajustamos el presupuesto a la viabilidad
                          de tu aplicación. Soluciones escalables, verás resultados en menos de cuatro semanas
                          desde la firma del proyecto.
	                    </v-card-text>
	                  </v-card>
	                </v-flex>
	                <v-flex xs12 md4>
	                  <v-card class="elevation-0 transparent">
	                    <v-card-text class="text-xs-center">
	                      <v-icon x-large class="blue--text text--lighten-2">flash_on</v-icon>
	                    </v-card-text>
	                    <v-card-title primary-title class="layout justify-center">
	                      <div class="headline">Desarrollo en Laravel</div>
	                    </v-card-title>
	                    <v-card-text>
	                      Desarrollo en Laravel 5 junto con Vue JS. Laravel es un framework de código abierto para
                          desarrollo de aplicaciones web con PHP 7 y su filosofía es desarrollar en PHP de forma elegante y simple, que
                          junto con Vue Js que es un JavaScript framework hacen una combinación perfecta para el desarrollo de aplicaciones
                          seguras y profesionales.
	                    </v-card-text>
	                  </v-card>
	                </v-flex>
	                <v-flex xs12 md4>
	                  <v-card class="elevation-0 transparent">
	                    <v-card-text class="text-xs-center">
	                      <v-icon x-large class="blue--text text--lighten-2">build</v-icon>
	                    </v-card-text>
	                    <v-card-title primary-title class="layout justify-center">
	                      <div class="headline text-xs-center">Entrega del proyecto</div>
	                    </v-card-title>
	                    <v-card-text>
	                      Una vez desarrollado tu proyecto nos encargamos de todo, alojamos tu aplicación en un servidor propio, con
                          tu propio dominio, gestionamos actualizaciones del servidor, backup... solo tendrás que preocuparte de la
                          gestión de tu negocio, déjamos la parte técnica a nosotros y despreocúpate de grandes inversiones.
	                    </v-card-text>
	                  </v-card>
	                </v-flex>
	              </v-layout>
	            </v-container>
	          </v-flex>
	        </v-layout>
	      </section>

	      <section>
	        <v-parallax src="assets/section.jpg" height="380">
	          <v-layout column align-center justify-center>
	            <div class="headline white--text mb-3 text-xs-center">Desarrollo fácil e intuitivo</div>
	            <em>Proyectos cerrados adaptados a tus necesidades</em>
	          </v-layout>
	        </v-parallax>
	      </section>

	      <section>
	        <v-container grid-list-xl>
	          <v-layout row wrap justify-center class="my-5">
	            <v-flex xs12 sm4>
	            </v-flex>
	            <v-flex xs12 sm4 offset-sm1>
	              <v-card class="elevation-0 transparent">
	                <v-card-title primary-title class="layout justify-center">
	                  <div class="headline">Contactar</div>
	                </v-card-title>
	                <v-list class="transparent">
	                  <v-list-tile>
	                    <v-list-tile-action>
	                      <v-icon class="blue--text text--lighten-2">phone</v-icon>
	                    </v-list-tile-action>
	                    <v-list-tile-content>
	                      <v-list-tile-title>+34 666 48 30 94</v-list-tile-title>
	                    </v-list-tile-content>
	                  </v-list-tile>
	                  <v-list-tile>
	                    <v-list-tile-action>
	                      <v-icon class="blue--text text--lighten-2">place</v-icon>
	                    </v-list-tile-action>
	                    <v-list-tile-content>
	                      <v-list-tile-title>Madrid, ES</v-list-tile-title>
	                    </v-list-tile-content>
	                  </v-list-tile>
	                  <v-list-tile>
	                    <v-list-tile-action>
	                      <v-icon class="blue--text text--lighten-2">email</v-icon>
	                    </v-list-tile-action>
	                    <v-list-tile-content>
	                      <v-list-tile-title>info@sanaval-tec.com</v-list-tile-title>
	                    </v-list-tile-content>
	                  </v-list-tile>
	                </v-list>
	              </v-card>
	            </v-flex>
	          </v-layout>
	        </v-container>
	      </section>
	    </v-content>
	  </v-app>

	 </div>
</template>
<script>
import {mapGetters} from 'vuex';
	export default {
        computed:{
            ...mapGetters([
				'isLoggedIn'
            ]),
        },
        methods:{
            login(){
                window.location = '/login';
            }
        }
	}
</script>
