import {
  mdiMonitor,
  mdiViewModule,
  mdiShieldKey,
  mdiShapeOutline,
  mdiViewDashboard, 
  mdiAccountMultiple , 
  mdiKeyChain ,
  mdiOfficeBuilding,         
  mdiTrademark,       
  mdiShieldAccount,    
  mdiClipboardList,       
  mdiLaptop,        
  mdiServer,          
  mdiClipboardFlow,    
  mdiCertificate,     
} from "@mdi/js";

export default [
// {
//     href: "/dashboard",
//     to: "/dashboard",
//     icon: mdiViewDashboard,
//     label: "Dashboard",
//     role: "Admin" //Vistas 
// },

{
  label: "Dashboard",
  icon: mdiViewDashboard,
  role: "Admin",
  menu: [
    {
        href:"/dashboard",
        label: "Inventario",
        icon: mdiClipboardList,
        role: "Admin",
    }, 
  
    
   
  ],
},

{
  label: "Seguridad",
  icon: mdiShieldKey,
  role: "Admin",
  menu: [
    {
        href:"/usuarios",
        label: "Usuarios",
        icon: mdiAccountMultiple,
        role: "Admin",
    }, 
    {
      route: "roles.index",
      label: "Roles",
      icon: mdiShieldAccount,
      role: "Admin",
    },
    
    {
      route: "modules.index",
      label: "Módulos",
      icon: mdiViewModule,
      role: "Admin",
    },
    {
      route: "permissions.index",
      label: "Permisos",
      icon: mdiKeyChain,
      role: "Admin",
    }, 
  ],
},
     







];
