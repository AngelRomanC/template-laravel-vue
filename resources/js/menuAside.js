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
    {
      route: "dashboard.procesos",
      label: "Procesos & Cerificaciones",
      icon: mdiShieldAccount,
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
      route: "permisos.index",
      label: "Permisos",
      icon: mdiKeyChain,
      role: "Admin",
    }, 
  ],
},
     
 {
    label: "Catálogos",
    icon: mdiShapeOutline ,
    role: "Admin",
    menu: [     
      {
        route: "marcas.index",
        label: "Registrar Marcas",
        icon: mdiTrademark ,
        role: "Admin",
      }, 
      {
        route: "departamentos.index",
        label: "Departamentos",
        icon: mdiOfficeBuilding ,
        role: "Admin",
      }, 
    ]
  },
  
    {
    route: "inventario.index",
    label: "Inventario Equipos",
    icon: mdiLaptop ,
    role: "Admin",
  }, 

    {
    route: "sistema.index",
    label: "Inventario Sistema",
    icon: mdiServer ,
    role: "Admin",
  }, 

  {
    route: "procesos.index",
    label: "Procesos",
    icon: mdiClipboardFlow ,
    role: "Admin",
  }, 

  {
    route: "certificacion.index",
    label: "Certificaciones",
    icon: mdiCertificate ,
    role: "Admin",
  },

  
 

  //Modulos a Desarrollador de sistema 
  {
    href: "/dashboard",
    to: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
    role: "Desarrollador" 
  },
  
  {
    label: "Catálogos",
    icon: mdiShapeOutline ,
    role: "Desarrollador",
    menu: [     
      {
        route: "marcas.index",
        label: "Registrar Marcas",
        icon: mdiTrademark ,
        role: "Desarrollador",
      }, 
    ]
  },
  {
    route: "inventario.index",
    //href:"/inventario",
    label: "Inventario Equipos",
    icon: mdiLaptop ,
    role: "Desarrollador",
  }, 

  {
    route: "sistema.index",
    //href:"/inventario",
    label: "Inventario Sistema",
    icon: mdiServer ,
    role: "Desarrollador",
  }, 

   
  
  
  //Modulos a Procesos de sistema ---------------------------------------
  {
    href: "/dashboard",
    to: "/dashboard",
    icon: mdiViewDashboard,
    label: "Dashboard",
    role: "Procesos" 
  },

  {
  label: "Seguridad",
  icon: mdiShieldKey,
  role: "Procesos",
  menu: [
    {
        href:"/usuarios",
        label: "Usuarios",
        icon: mdiAccountMultiple,
        role: "Procesos",
    }, 
   
   ],
  },
  
  {
    label: "Catálogos",
    icon: mdiShapeOutline ,
    role: "Procesos",
    menu: [     
   
      {
        route: "departamentos.index",
        label: "Departamentos",
        icon: mdiOfficeBuilding ,
        role: "Procesos",
      }, 
    ]
  },

  {
    route: "procesos.index",
    label: "Procesos",
    icon: mdiClipboardFlow ,
    role: "Procesos",
  }, 

  {
    route: "certificacion.index",
    label: "Certificaciones",
    icon: mdiCertificate ,
    role: "Procesos",
  },
  
  //Modulos a Ejecutivo de sistema 

  {
    href: "/dashboard-ejecutivo",
    icon: mdiViewDashboard,
    label: "Dashboard",
    role: "Ejecutivo"
  },

  {
    route: "procesos.index",
    label: "Procesos",
    icon: mdiClipboardFlow ,
    role: "Ejecutivo",
  }, 






];
