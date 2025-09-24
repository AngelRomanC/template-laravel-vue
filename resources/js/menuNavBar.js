import {
  mdiMenu,
  mdiClockOutline,
  mdiCloud,
  mdiCrop,
  mdiAccount,
  mdiCogOutline,
  mdiEmail,
  mdiLogout,
  mdiThemeLightDark,
  mdiGithub,
  mdiReact,
  mdiBell,
  mdiAccountSwitch
} from "@mdi/js";

import { usePage } from "@inertiajs/vue3";
import axios from 'axios';

export default [
  {
    isCurrentUser: true,
    menu: [
      {
        href:"/profile",
        icon: mdiAccount,
        label: "Mi perfil",
        to: "/profile",
      },
      {
        isDivider: true,
      },
    ],
  },
  {
    icon: mdiThemeLightDark,
    label: 'Light/Dark',
    isDesktopNoLabel: true,
    isToggleLightDark: true,
  },
  {
    icon: mdiAccount,
    isRol: true,
  },
  {
    icon: mdiAccountSwitch,
    label: "Cambiar Rol",
    isDesktopNoLabel: true,
    isSwitchRole: true,
  },
  {
    icon: mdiLogout,
    label: "Log out",
    isDesktopNoLabel: true,
    isLogout: true,
  },
];
