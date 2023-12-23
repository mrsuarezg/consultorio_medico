export default [
    {
        heading: 'Bienvenido',
        gate: '',
    },
    {
        title: 'Dashboard',
        to: 'dashboard',
        icon: { icon: 'mdi-view-dashboard', color: 'primary' },
        gate: null,
    },
    {
        title: 'Pacientes',
        to: 'patients',
        icon: { icon: 'mdi-account-group', color: 'primary' },
        gate: null,
    },
    {
        title: 'Consultas',
        to: 'consultations',
        icon: { icon: 'mdi-calendar-check', color: 'primary' },
        gate: null,
    },
    {
        title: 'Usuarios',
        to: 'users',
        icon: { icon: 'mdi-account-multiple', color: 'primary' },
        gate: 'admin',
    }
];
