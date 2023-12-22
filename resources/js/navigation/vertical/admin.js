export default [
    {
        heading: 'Administración',
        gate: 'admin|executive|finance',
    },
    {
        title: 'Reporteo',
        gate: 'admin|executive|finance',
        icon: { icon: 'mdi-microsoft-excel' },
        to: 'pain',
    },
    {
        title: 'Registros',
        gate: 'admin',
        icon: { icon: 'mdi-table-large' },
        children: [
            {
                title: 'Centro de costos',
                icon: { icon: 'mdi-office-building' },
                gate: 'admin',
                to: 'settings',
                section: 'cost-center',
            },
            {
                title: 'Clientes',
                icon: { icon: 'mdi-card-account-details' },
                gate: 'admin',
                to: 'settings',
                section: 'customers',
            },
            {
                title: 'Insumos',
                to: 'settings',
                gate: 'admin',
                icon: { icon: 'mdi-cube' },
                section: 'supplies',
            },
            {
                title: 'Proveedores',
                icon: { icon: 'mdi-truck-flatbed' },
                gate: 'admin',
                to: 'settings',
                section: 'suppliers',
            },
            {
                title: 'Tarjetas empresariales',
                icon: { icon: 'mdi-credit-card-chip' },
                gate: 'admin',
                to: 'settings',
                section: 'business-card',
            },
        ],
    },
    {
        title: 'Usuarios',
        to: 'users.index',
        gate: 'admin|users',
        icon: { icon: 'mdi-account-group' },
    },
    {
        title: 'Roles',
        to: 'roles.index',
        gate: 'admin|roles',
        icon: { icon: 'mdi-account-supervisor' },
    },
    {
        title: 'Permisos',
        to: 'permissions.index',
        gate: 'admin|permissions',
        icon: { icon: 'mdi-account-key' },
    },
]
