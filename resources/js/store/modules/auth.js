/*
|--------------------------------------------------------------------------
| Mutation Types
|--------------------------------------------------------------------------
*/
export const SET_USER = 'SET_USER';
export const UNSET_USER = 'UNSET_USER';

/*
|--------------------------------------------------------------------------
| Initial State
|--------------------------------------------------------------------------
*/
const initialState = {
    id: null,
	name: null,
    username: null,
    avatar: null,
    empresa_id: null,
    roles: [],
    permisos: []
};

/*
|--------------------------------------------------------------------------
| Mutations
|--------------------------------------------------------------------------
*/
const mutations = {
	[SET_USER](state, payload) {
        state.id = payload.user.id;
		state.name = payload.user.name;
        state.username = payload.user.username;
        state.avatar = payload.user.avatar;
        state.empresa_id = payload.user.empresa_id;
        state.roles = payload.user.roles;
        state.permisos = payload.user.permisos;
	},
	[UNSET_USER](state, payload) {
        state.id = null;
        state.name = null;
		state.name = null;
        state.username = null;
        state.avatar = null;
        state.empresa_id=null;
        state.roles = [];
        state.permisos = [];
	}
};

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
*/
const actions = {
	setAuthUser: (context, user) => {
		context.commit(SET_USER, {user})
	},
	unsetAuthUser: (context) => {
		context.commit(UNSET_USER);
	}
};

/*
|--------------------------------------------------------------------------
| Getters
|--------------------------------------------------------------------------
*/
const getters = {
    userId: (state) =>{
        return state.id
    },
    empresaActiva: (state) =>{
        return state.empresa_id
    },
	isLoggedIn: (state) => {
		return !!(state.name && state.username);
    },
    getRoles: (state) =>{
        return state.roles
    },
    isRoot: (state) =>{
        return (state.roles.indexOf('Root') >= 0) ? true : false;

    },
    isAdmin: (state) =>{
        return (state.roles.indexOf('Admin') >= 0) ? true : false;

    },
    hasDocumenta: (state) =>{
        return (state.roles.indexOf('Documenta') >= 0) ? true : false;
    },
    hasFactura: (state) =>{
        return (state.roles.indexOf('Factura') >= 0) ? true : false;
    },
    hasBorraDoc: (state) =>{
        return (state.permisos.indexOf('Borra documentos') >= 0) ? true : false;
    },

};

/*
|--------------------------------------------------------------------------
| Export the module
|--------------------------------------------------------------------------
*/
export default {
	state: initialState,
	mutations,
	actions,
	getters
}
