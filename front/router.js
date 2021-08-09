import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  mode: 'hash',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      component: () => import('@/views/dashboard/Index'),
      children: [
        // Dashboard
        {
          name: 'Dashboard',
          path: '',
          component: () => import('@/views/dashboard/Dashboard'),
        },
        // Package
        // {
        //   name: 'Package',
        //   path: 'package',
        //   component: () => import('@/views/dashboard/pages/report/Package'),
        // },
        // Manual
        {
          name: 'Список пород',
          path: 'manual/species',
          component: () => import('@/views/dashboard/pages/manual/Species'),
        },
        {
          name: 'Люди',
          path: 'manual/people',
          component: () => import('@/views/dashboard/pages/manual/People'),
        },
        {
          name: 'Рабочее расписание',
          path: 'manual/workschedule',
          component: () => import('@/views/dashboard/pages/manual/WorkSchedule'),
        },
        {
          name: 'Простои',
          path: 'manual/downtime',
          component: () => import('@/views/dashboard/pages/manual/Downtime'),
        },
        {
          name: 'Ошибки',
          path: 'manual/error',
          component: () => import('@/views/dashboard/pages/manual/Error'),
        },
        {
          name: 'Действия оператора',
          path: 'manual/action',
          component: () => import('@/views/dashboard/pages/manual/Action'),
        },
        {
          name: 'Стандартные длины',
          path: 'manual/standartlength',
          component: () => import('@/views/dashboard/pages/manual/StandartLength'),
        },
        //reports 
        {
          name: 'Простои ',
          path: 'report/downtimes',
          component: () => import('@/views/dashboard/pages/report/Downtime'),
        },
        {
          name: 'Аварии и сообщения',
          path: 'report/alert',
          component: () => import('@/views/dashboard/pages/report/event/Alert'),
        },
        {
          name: 'Действия оператора ',
          path: 'report/action',
          component: () => import('@/views/dashboard/pages/report/event/Action'),
        },
        {
          name: 'Операторы',
          path: 'report/operators',
          component: () => import('@/views/dashboard/pages/report/Operator'),
        },
        {
          name: 'Пиломатериалы',
          path: 'report/board',
          component: () => import('@/views/dashboard/pages/report/Board'),
        },
        {
          name: 'Хронология пиломатериалов',
          path: 'report/boardregistry',
          component: () => import('@/views/dashboard/pages/report/RegistryBoard'),
        },
        {
          name: 'Выгруженные карманы',
          path: 'report/unload',
          component: () => import('@/views/dashboard/pages/report/Unload'),
        },
        {
          name: 'Остатки в карманах',
          path: 'report/balancepocket',
          component: () => import('@/views/dashboard/pages/report/BalancePocket'),
        },
        {
          name: 'Отбойник',
          path: 'report/lastpocket',
          component: () => import('@/views/dashboard/pages/report/LastPocket'),
        },
      ],
    },
    {
      path: '/showpackage',
      component: () => import('@/views/singlePages/Package'),
    },
    // {
      // path: '/login/',
      // component: () => import('@/views/auth/Login'),
    // },
    {
      path: '/report',
      component: () => import('@/views/singlePages/Report'),
    },
    {
      path: '/preloader',
      component: () => import('@/views/Preloader'),
    },
  ],
})
