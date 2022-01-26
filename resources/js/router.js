import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import store from './store/index';
import routes from './routes';
import {api} from "./config";
//import { request } from 'https';

const router = new VueRouter({
	mode: 'history',
    routes,
    scrollBehavior(){
		return{x:0, y:0};
}
});



router.beforeEach(async (to, from, next) => {
    //console.log(to);
//     // just logged in: localStorage has token, but state does not have auth user

//     // if(jwtToken.exp < Date.now()){
//     //     //console.log(jwtToken.getToken());
//     //     //token is valid, do your stuff
//     //   }else {
//     //     //console.log('expirada');
//     //     jwtToken.removeToken();
//     //     //return next({name: 'login'});
//     //     //token expired, regenerate it and set it to the cookie
//     //     //also update the expire time of the cookie
//     //   }

// 	if (!store.getters.isLoggedIn) {
//         axios.get('/dash')
//             .then(res => {
//                 store.dispatch('setAuthUser', res.data.user);

//                 // if (res.data.role.root){
//                 //     this.items.push(this.root);
//                 // }else if(res.data.role.admin){
//                 //     this.items.push(this.admin);
//                 // }
//             })
//             .catch(err => {
//                 console.log(err.request)
//             })

//         // console.log('beforeEach');
//         // let {data: authUser} = await axios.get('/dash')
//         //     .catch(err => {
//         //         console.log(err);
//         //     });

// 		// await store.dispatch('setAuthUser', authUser);
// 	}

// 	// if (to.meta.requiresAuth) {

// 	// 	if (store.getters.isLoggedIn)
// 	// 		return next();
// 	// 	else
// 	// 		return next({name: 'login'});
// 	// }

	 next();
});

export default router;
