import Home from './components/Home.vue';
import Dash from './components/Dash.vue';
import Main from './components/Main.vue';
import st404 from './components/shared/404.vue';

import UserIndex from './components/admin/users/UserIndex.vue';
import UserCreate from './components/admin/users/UserCreate.vue';
import UserEdit from './components/admin/users/UserEdit.vue';
import RolesIndex from './components/admin/roles/RolesIndex.vue';
import RolesCreate from './components/admin/roles/RolesCreate.vue';
import RolesEdit from './components/admin/roles/RolesEdit.vue';

import RetIndex from './components/admin/retenciones/RetIndex.vue';
import RetCreate from './components/admin/retenciones/RetCreate.vue';
import RetEdit from './components/admin/retenciones/RetEdit.vue';

import IvaIndex from './components/admin/iva/IvaIndex.vue';
import IvaCreate from './components/admin/iva/IvaCreate.vue';
import IvaEdit from './components/admin/iva/IvaEdit.vue';

import ArchivoIndex from './components/admin/archivos/ArchivoIndex.vue';
import ArchivoCreate from './components/admin/archivos/ArchivoCreate.vue';
import ArchivoEdit from './components/admin/archivos/ArchivoEdit.vue';

import CarpetaIndex from './components/carpetas/CarpetaIndex.vue';
import CarpetaCreate from './components/carpetas/CarpetaCreate.vue';
import CarpetaEdit from './components/carpetas/CarpetaEdit.vue';

import EmpresaIndex from './components/admin/empresas/EmpresaIndex.vue';
import EmpresaCreate from './components/admin/empresas/EmpresaCreate.vue';
import EmpresaEdit from './components/admin/empresas/EmpresaEdit.vue';

import FpagoIndex from './components/admin/fpago/FpagoIndex.vue';
import FpagoCreate from './components/admin/fpago/FpagoCreate.vue';
import FpagoEdit from './components/admin/fpago/FpagoEdit.vue';

import VencimientoIndex from './components/admin/vencimientos/VencimientoIndex.vue';
import VencimientoCreate from './components/admin/vencimientos/VencimientoCreate.vue';
import VencimientoEdit from './components/admin/vencimientos/VencimientoEdit.vue';


import ContadorIndex from './components/admin/contador/ContadorIndex.vue';
import ContadorCreate from './components/admin/contador/ContadorCreate.vue';
import ContadorEdit from './components/admin/contador/ContadorEdit.vue';

import CuentaIndex from './components/admin/cuentas/CuentaIndex.vue';
import CuentaCreate from './components/admin/cuentas/CuentaCreate.vue';
import CuentaEdit from './components/admin/cuentas/CuentaEdit.vue';


import ClienteIndex from './components/clientes/ClienteIndex.vue';
import ClienteCreate from './components/clientes/ClienteCreate.vue';
import ClienteEdit from './components/clientes/ClienteEdit.vue';
import ClienteShow from './components/clientes/ClienteShow.vue';
import ProductoIndex from './components/productos/ProductoIndex.vue';
import ProductoCreate from './components/productos/ProductoCreate.vue';
import ProductoEdit from './components/productos/ProductoEdit.vue';

import AlbaranIndex from './components/albaranes/AlbaranIndex.vue';
import AlbaranCreate from './components/albaranes/AlbaranCreate.vue';
import AlbaranEdit from './components/albaranes/AlbaranEdit.vue';

import ExtractoIndex from './components/extractos/ExtractoIndex.vue';
import ExtDocRel from './components/extractos/ExtDocRel.vue';
import ExtractoImp from './components/extractos/Importar.vue';

import SelRemesa from './components/adeudos/SelRemesa.vue';

import DocumentoIndex from './components/documentos/DocumentoIndex.vue';
import DocumentoCreate from './components/documentos/DocumentoCreate.vue';
import DocumentoEdit from './components/documentos/DocumentoEdit.vue';
import DocumentoShow from './components/documentos/DocumentoShow.vue';
import DocumentoDownload from './components/documentos/Download.vue';

import RemesaIndex from './components/remesas/RemesaIndex.vue';
import RemesaCreate from './components/remesas/RemesaCreate.vue';
import RemesaEdit from './components/remesas/RemesaEdit.vue';

import TransferenciaSepa from './components/remesas/TransferenciaSepa.vue';
import AdeudoSepa from './components/remesas/AdeudoSepa.vue';

import PdfRemesa from './components/facturas/PdfRemesa.vue';

import EditPassword from './components/profile/edit-password/EditPassword.vue';

export default [
	{
		path: '/',
		name: 'index',
		component: Home
    },
	{
		path: '/login',
		name: 'login'
    },
    {
		path: '/password/reset',
		name: 'password.reset'
    },
	{
		path: '/home',
        component: Dash,
        children: [
			{
				path: '',
				name: 'dash',
                component: Main,
            },
            {
                path: '/users',
                name: 'users.index',
                component: UserIndex,
            },
            {
                path: '/users/create',
                name: 'users.create',
                component: UserCreate,
            },
            {
                path: '/users/:id/edit',
                name: 'users.edit',
                component: UserEdit,
            },
            {
                path: '/roles',
                name: 'roles',
                component: RolesIndex,
            },
            {
                path: '/roles/create',
                name: 'roles_create',
                component: RolesCreate,
            },
            {
                path: '/roles/:id/edit',
                name: 'roles_edit',
                component: RolesEdit,
            },
            {
                path: '/users/password',
                name: 'edit.password',
                component: EditPassword,
            },
            {
                path: '/retenciones',
                name: 'ret.index',
                component: RetIndex,
            },
            {
                path: '/retenciones/create',
                name: 'ret.create',
                component: RetCreate,
            },
            {
                path: '/retenciones/:id/edit',
                name: 'ret.edit',
                component: RetEdit,
            },
            {
                path: '/ivas',
                name: 'iva.index',
                component: IvaIndex,
            },
            {
                path: '/ivas/create',
                name: 'iva.create',
                component: IvaCreate,
            },
            {
                path: '/ivas/:id/edit',
                name: 'iva.edit',
                component: IvaEdit,
            },
            {
                path: '/archivos',
                name: 'archivo.index',
                component: ArchivoIndex,
            },
            {
                path: '/archivos/create',
                name: 'archivo.create',
                component: ArchivoCreate,
            },
            {
                path: '/archivos/:id/edit',
                name: 'archivo.edit',
                component: ArchivoEdit,
            },
            {
                path: '/carpetas',
                name: 'carpeta.index',
                component: CarpetaIndex,
            },
            {
                path: '/carpetas/create',
                name: 'carpeta.create',
                component: CarpetaCreate,
            },
            {
                path: '/carpetas/:id/edit',
                name: 'carpeta.edit',
                component: CarpetaEdit,
            },
            {
                path: '/empresas',
                name: 'empresa.index',
                component: EmpresaIndex,
            },
            {
                path: '/empresas/create',
                name: 'empresa.create',
                component: EmpresaCreate,
            },
            {
                path: '/empresas/:id/edit',
                name: 'empresa.edit',
                component: EmpresaEdit,
            },
            {
                path: '/clientes',
                name: 'cliente.index',
                component: ClienteIndex,
            },
            {
                path: '/clientes/create',
                name: 'cliente.create',
                component: ClienteCreate,
            },
            {
                path: '/clientes/:id/edit',
                name: 'cliente.edit',
                component: ClienteEdit,
            },
            {
                path: '/clientes/:id',
                name: 'cliente.show',
                component: ClienteShow,
            },
            {
                path: '/fpagos',
                name: 'fpago.index',
                component: FpagoIndex,
            },
            {
                path: '/fpagos/create',
                name: 'fpago.create',
                component: FpagoCreate,
            },
            {
                path: '/fpagos/:id/edit',
                name: 'fpago.edit',
                component: FpagoEdit,
            },
            {
                path: '/vencimientos',
                name: 'vencimiento.index',
                component: VencimientoIndex,
            },
            {
                path: '/vencimientos/create',
                name: 'vencimiento.create',
                component: VencimientoCreate,
            },
            {
                path: '/vencimientos/:id/edit',
                name: 'vencimiento.edit',
                component: VencimientoEdit,
            },
            {
                path: '/productos',
                name: 'producto.index',
                component: ProductoIndex,
            },
            {
                path: '/productos/create',
                name: 'producto.create',
                component: ProductoCreate,
            },
            {
                path: '/productos/:id/edit',
                name: 'producto.edit',
                component: ProductoEdit,
            },
            {
                path: '/contadores',
                name: 'contador.index',
                component: ContadorIndex,
            },
            {
                path: '/contadores/create',
                name: 'contador.create',
                component: ContadorCreate,
            },
            {
                path: '/contadores/:id/edit',
                name: 'contador.edit',
                component: ContadorEdit,
            },
            {
                path: '/albaranes',
                name: 'albaran.index',
                component: AlbaranIndex,
            },
            {
                path: '/albaranes/create',
                name: 'albaran.create',
                component: AlbaranCreate,
            },
            {
                path: '/albaranes/:id/edit',
                name: 'albaran.edit',
                component: AlbaranEdit,
            },
            {
                path: '/facturas/print',
                name: 'facturas.print',
                component: PdfRemesa,
            },
            {
                path: '/adeudos',
                name: 'adeudo.index',
                component: SelRemesa,
            },
            {
                path: '/cuentas',
                name: 'cuenta.index',
                component: CuentaIndex,
            },
            {
                path: '/cuentas/create',
                name: 'cuenta.create',
                component: CuentaCreate,
            },
            {
                path: '/cuentas/:id/edit',
                name: 'cuenta.edit',
                component: CuentaEdit,
            },
            {
                path: '/extractos',
                name: 'extracto.index',
                component: ExtractoIndex,
            },
            {
                path: '/extractos/link',
                name: 'extracto.link',
                component: ExtDocRel,
            },
            {
                path: '/extractos/importar',
                name: 'extracto.importar',
                component: ExtractoImp,
            },
            {
                path: '/documentos',
                name: 'documento.index',
                component: DocumentoIndex,
            },
            {
                path: '/documentos/create',
                name: 'documento.create',
                component: DocumentoCreate,
            },
            {
                path: '/documentos/:id/edit',
                name: 'documento.edit',
                component: DocumentoEdit,
            },
            {
                path: '/documentos/:id/show',
                name: 'documento.show',
                component: DocumentoShow,
            },
            {
                path: '/documentos/download',
                name: 'documento.zip',
                component: DocumentoDownload,
            },
            {
                path: '/remesas',
                name: 'remesa.index',
                component: RemesaIndex,
            },
            {
                path: '/remesas/create',
                name: 'remesa.create',
                component: RemesaCreate,
            },
            {
                path: '/remesas/:id/edit',
                name: 'remesa.edit',
                component: RemesaEdit,
            },
            {
                path: '/sepa/transfer',
                name: 'sepa.transfer',
                component: TransferenciaSepa,
            },
            {
                path: '/sepa/adeudo',
                name: 'sepa.adeudo',
                component: AdeudoSepa,
            },
            // {
            //     path: '*',
            //     redirect: {
            //         name: 'index'
            //     }
            // }
        ]
    },
    {
        path: '*',
        name: '404',
        component: st404,
    }
];
