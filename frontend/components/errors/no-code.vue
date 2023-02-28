<template>
    <div class="px-4 pb-4">
        <div class="error-panel bg-404">
          <div class="text-center">
            <h1>{{ error.statusCode }}</h1>
            <h4>Error</h4>
            <p>{{ error.message }}</p>
          </div>
        </div>
    </div>
  </template>
  <style>
    .bg-404{
      background: url(/img/errors/400.png) no-repeat center center rgba(255, 255, 255, 0.945); 
      background-blend-mode: multiply;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
    }
  </style>
  <script>
  export default {
    name: 'error-404',
    props: ['error']
  };
  </script>
  