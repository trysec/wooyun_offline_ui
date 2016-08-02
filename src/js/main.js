var app = angular.module('myapp', ['ngCookies', 'angularLocalStorage', 'bw.paging']);
app.controller('main', ['$scope', '$http', 'storage',
    function($scope, $http, storage) {
        $scope.pageSize = 10;
        $scope.currentPage = 1;

        $scope.credits = {
            'any': '不限',
            'm1': '通用 1',
            'm2': '通用 2',
            'm3': '通用 3',
            'credit': '漏洞预警'
        }

        // storage binding
        var values = {
          credit: 'any',
          keyword: ''
        }

        Object.keys(values).forEach(function(key) {
          storage.bind($scope, key, {
            defaultValue: values[key]
          });
        });

        $scope.setCredit = function(credit) {
            $scope.credit = credit;
            $scope.search(1);
        }

        $scope.search = function(page) {
            if (page != undefined) {
                $scope.currentPage = page;
            }

            $http.post('api.php', {
                credit: $scope.credit,
                keyword: $scope.keyword,
                limit: $scope.pageSize,
                skip: ($scope.currentPage - 1) * $scope.pageSize
            }).then(function(res) {
                res = res.data;
                $scope.data = res.data;
                $scope.count = res.count;
            })
        }

        $scope.search();
    }
]);