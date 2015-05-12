'use strict';

/**
 * Aplicação Matrix2015 - questionário de avaliação
 */
var app = angular.module('app',
        ['ui.router', 'ngAnimate',
            'ui.bootstrap', 'ngFoobar',
            'ngStorage', 'nvd3']);

/**
 * Rotas
 */
app.config(['$stateProvider', '$urlRouterProvider', function ($r, $t) {

        $t.when('/dashboard', '/dashboard/overview'), $t.otherwise('/login'), $r.state('base', {
            'abstract': !0,
            url: '',
            templateUrl: 'views/base.html'
        }).state('login', {
            url: '/login',
            parent: 'base',
            templateUrl: 'views/login.html',
            controller: 'LoginCtrl'
        }).state('dashboard', {
            url: '/dashboard',
            parent: 'base',
            templateUrl: 'views/dashboard.html',
            controller: 'DashboardCtrl'
        }).state('overview', {
            url: '/overview',
            parent: 'dashboard',
            templateUrl: 'views/dashboard/overview.html'
        }).state('reports', {
            url: '/reports',
            parent: 'dashboard',
            templateUrl: 'views/dashboard/reports.html',
            controller: 'GraficoCtrl'
        }).state('sobre', {
            url: '/sobre',
            parent: 'dashboard',
            templateUrl: 'views/dashboard/sobre.html'
        }).state('admin', {
            url: '/admin',
            parent: 'dashboard',
            templateUrl: 'views/dashboard/admin.html',
            controller: 'AdminSenhaCtrl'
        }).state('senha', {
            url: '/senha',
            parent: 'dashboard',
            templateUrl: 'views/dashboard/senha.html',
            controller: 'AlterarSenhaCtrl'
        })
    }]);

/**
 * Login
 */
app.controller('LoginCtrl',
        ['$scope', '$rootScope', '$localStorage', '$location', '$http', 'ngFoobar',
            function ($s, $r, $localStorage, $t, $h, ngFoobar) {

                console.log('LoginCtrl');
                $s.login = function () {

                    //info de carregamento
                    $s.dataLoading = true;
                    //carrega dados do usuário e verifica login
                    $h.get($r.HOST_LOCAL + "rest/dev/search/" + $s.username)
                            .success(function (response) {

                                var n1 = $s.username.toUpperCase();
                                var s1 = $s.password;
                                if (response.records.length > 0) {
                                    var n2 = response.records[0].nome.toUpperCase();
                                    var s2 = response.records[0].senha;
                                }
                                else
                                {
                                    var n2 = '';
                                    var n2 = '';
                                }

                                if (n1 === n2 && s1 === s2) {

                                    //root scope
                                    $r.username = $s.username;
                                    $r.desenvolvedor = $s.username;
                                    $r.time = response.records[0].time;
                                    $r.admin = response.records[0].admin;
                                    $r.super = $s.admin === 'S';
                                    $r.senha = $s.password;
                                    //local storage
                                    $localStorage.username = $s.username;
                                    $localStorage.time = response.records[0].time;
                                    $localStorage.admin = response.records[0].admin;
                                    $localStorage.desenvolvedor = $s.username;
                                    $localStorage.localHost = $s.localHost;
                                    $r.desenvolvedor = $s.username;
                                    $s.dataLoading = false;
                                    return $t.path('/dashboard'), !1;
                                } else
                                {
                                    $s.dataLoading = false;
                                    ngFoobar.show("error", "Usuário ou senha inválida!");
                                    return $t.path('/login'), !1;
                                }
                            })
                            .error(function (data) {
                                $s.dataLoading = false;
                                ngFoobar.show("error", "Usuário ou senha inválida!");
                                return $t.path('/login'), !1;
                            });
                }
            }]);
/**
 *  Contralador dapágina principal
 */
app.controller('OverviewCtrl',
        ['$scope', '$rootScope', '$location', '$http',
            '$localStorage', 'ngFoobar',
            function ($s, $r, $t, $h, $localStorage, ngFoobar) {

                console.log('OverviewCtrl');
                //inicia progresso do preenchimento
                $s.radioModel = [];
                $s.max = 0;
                $s.posi = 0;
                
                //recupera scopo
                //código abaixo será refatorado
                if (typeof $localStorage.username === "undefined") {
                    console.log('teste local nao encontrado');
                    return $t.path('/login'), !1;
                } else
                {
                    $r.username = $localStorage.username;
                    $r.time = $localStorage.time;
                    $r.admin = $localStorage.admin;
                    $r.desenvolvedor = $localStorage.desenvolvedor;
                }

                //scopo local
                //para refatorado
                $s.username = $r.username;
                $s.desenvolvedor = $r.desenvolvedor;
                //inicia arrays de avaliação e desenvolvedores
                $s.modulos = [];
                $s.devs = [];
                //aviso de alteração de senha
                
                if ($r.senha === '123') {
                    ngFoobar.show("error", "Senha insegura, altere sua senha!");
                }


                //carrega modulos da avaliação
                $h.get($r.HOST_LOCAL + 'rest/item/bydev/{ "avaliador":"' + $r.username + '","avaliado":"' + $r.desenvolvedor + '"}')
                        .success(function (response) {
                            $s.modulos = response.records;
                            $s.max = response.records.length;
                            //carrega total de questões preencidas   
                            $h.get($localStorage.HOST_LOCAL + 'rest/item/count/{ "avaliador":"' + $r.username + '","avaliado":"' + $r.desenvolvedor + '"}')
                                    .success(function (response) {
                                        $s.posi = response.records[0].total;
                                        $s.completou = $s.posi == $s.max;
                                    });
                        });
                //carrega lista desenvolvedores por time
                $h.get($r.HOST_LOCAL + "rest/time/" + $r.time)
                        .success(function (response) {
                            $s.devs = response.records;
                        });
                //marcar opcao da avaliação
                $s.marcarOpcao = function ($index, $op) {

                    //para refatoração 
                    var p = $r.HOST_LOCAL +
                            "rest/item/merge/" +
                            '{"id":' + $s.modulos[$index].id.toString() + '' +
                            ',"nome":"' + $r.username + '"' +
                            ',"enquete_id":1' +
                            ',"pergunta":' + $s.modulos[$index].pergunta_id.toString() + '' +
                            ',"resposta":' + $op.toString() + '}';
                    $h.get(p)
                            .success(function (response) {
                                //atualizar progresso
                                //para refatoração
                                var x = 0;
                                var i;
                                for (i = 0; i < $s.modulos.length; i++) {
                                    if ($s.modulos[i].resposta != '0') {
                                        x = x + 1;
                                    }
                                    $s.posi = x;
                                    $s.completou = $s.posi == $s.max;
                                }

                            })
                            .error(function (data) {
                            });
                }


                $s.selecionarDev = function ($index) {

                    //vou refatorar o código abaixo
                    $s.desenvolvedor = $s.devs[$index].nome;
                    $r.desenvolvedor = $s.devs[$index].nome;
                    $h.get($r.HOST_LOCAL + 'rest/item/bydev/{ "avaliador":"' + $r.username + '","avaliado":"' + $r.desenvolvedor + '"}')
                            .success(function (response) {
                                $s.modulos = response.records;
                                $s.max = response.records.length;
                                //atualiza progresso
                                //para refatoração
                                var x = 0;
                                var i = 0;
                                for (i = 0; i < $s.modulos.length; i++) {
                                    if ($s.modulos[i].resposta != 0) {
                                        x = x + 1;
                                    }
                                }
                                $s.posi = x;
                                $s.completou = $s.posi === $s.max;
                            })
                            .error(function (data) {
                                return $t.path('/login'), !1;
                            });
                }

            }]);
/**
 *  Controlador do painel lateraldo dashboard
 */
app.controller('DashboardCtrl', ['$scope', '$rootScope', '$state', '$localStorage',
    function ($s, $r, $t, $ls) {

        console.log('DashboardCtrl');
        //
        $s.username = $ls.username;
        $s.password = $ls.password;
        $s.$state = $t;
        //limpa dados usuário ao sair
        $s.logout = function () {
            $r.username = '';
            $r.password = '';
        }

    }]);

/**
 * 
 * Controlador dos gráficos
 */
app.controller('GraficoCtrl', ['$scope', '$rootScope', '$state', '$localStorage', '$sessionStorage', '$http',
    function ($s, $r, $t, $ls, $ss, $h) {

        console.log('GraficoCtrl');
  
   $s.options2 = {
            chart: {
                type: 'discreteBarChart',
                height: 450,
                margin : {
                    top: 20,
                    right: 20,
                    bottom: 60,
                    left: 55
                },
                x: function(d){return d.label;},
                y: function(d){return d.value;},
                showValues: true,
                valueFormat: function(d){
                    return d3.format(',.1f')(d)+'%';//',.1f'
                },
                transitionDuration: 500,
                xAxis: {
                    axisLabel: 'X Axis'
                },
                yAxis: {
                    axisLabel: 'Y Axis',
                    axisLabelDistance: 30
                }
            }
        };


       //atualizacao datasource
        $s.fetchData2 = function () {
            var p = $r.HOST_LOCAL + 'rest/grafico';
            var r = [{ key:'N/A' , values:[] }];
            $h.get(p)
                    .success(function (response) {
                        if (response.records.length === 0) {
                            $s.data2 = r;
                        }
                        else
                        {
                            $s.data2 = [{ key:'' , values:[] }];
                            $s.data2[0].key = "Cumulative Return";
                            $s.data2[0].values = response.records;
                        }
                    })
                    .error(function (data) {
                        $s.data2 = r;
                    });
        }
        $s.fetchData2();


    }]);
/**
 *  Controlador da alteração de sennha
 *  @param {type} param
 */
app.controller('AlterarSenhaCtrl', ['$scope', '$rootScope', '$localStorage', '$state', '$http', '$location', 'ngFoobar',
    function ($s, $r, $ls, $t, $h, $l, ngFoobar) {

        console.log('AlterarSenha');
        $s.senha1 = '';
        $s.senha2 = '';
        $s.alterarSenha = function () {


            if ($s.senha1.length < 6) {
                ngFoobar.show("error", "Senha muito curta, No mínimo 6 caracteres.");
            }
            else if ($s.senha1 === $s.senha2) {
                var d = $ls.HOST_LOCAL + 'rest/dev/senha/{"nome":"' + $s.username + '","senha":"' + $s.senha2 + '"}';
                $h.get(d)
                        .success(function (response) {
                            $r.senhax = $s.senha2;
                            return $l.path('/dashboard'), !1;
                        });
            }
        }


    }]);
/**
 *  Controlador da administração
 *  @param {type} param
 */
app.controller('AdminSenhaCtrl', ['$scope', '$rootScope', '$localStorage', '$state', '$http', '$location',
    function ($s, $r, $ls, $t, $h, $l) {

        console.log('AdminSenha');

        if ($r.super !== true)
        {
            return $l.path('/login'), !1;
        }
    }]);
/**
 * Contexto ao iniciar app
 * @param {type} param
 */
app.run(['$rootScope', '$localStorage', function ($r, $ls) {

        console.log('app.run');
        $r.username = '';
        $r.password = '';
        $r.time = '';
        $r.admin = '';
        $r.super = false;

        var host = 'LOCAL';
        if (host === 'LOCAL') {
            $ls.HOST_LOCAL = 'http://localhost/matrix/';
        }
        else if (host === 'MYCOMP')
        {
            $ls.HOST_LOCAL = 'http://172.27.10.246:88/matrix/';
        }
        else if (host = 'NITRO')
        {
            $ls.HOST_LOCAL = 'http://matrix-224500.sae1.nitrousbox.com/matrix2015/';
        }

        $r.HOST_LOCAL = $ls.HOST_LOCAL;
        console.log($r.HOST_LOCAL);
    }]);