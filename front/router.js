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
          name: 'Доски',
          path: 'report/board',
          component: () => import('@/views/dashboard/pages/report/Board'),
        },          
        {
          name: 'Хронология досок',
          path: 'report/boardregistry',
          component: () => import('@/views/dashboard/pages/report/RegistryBoard'),
        },        
      ],
    },
  ],
})
