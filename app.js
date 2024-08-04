var app = angular.module('miApp', ["ngRoute"]);
app.config(function($routeProvider){
    $routeProvider
        .when("/", {
            controller: "MainController",
            controllerAs: "mc",
            templateUrl: "componentes/productos.html"
        });
})
app.controller('MainController', function($scope, $http) {

    $scope.backupProducto = null;
    $scope.message = false;

    $scope.listProducts = function() {
        $http.get('fetch_data.php')
        .then(function(response) {
        $scope.productos = response.data.productos;
        $scope.categoria = response.data.categoria;
        
        $scope.modoEdicion = false
        }, function(error) {
            console.error('Error:', error);
        });
    }

    $scope.addProduct = function(){
        
        $scope.message = false;

        var data = {
            type: 'Insert',
            category: $scope.producto?.category,
            code: $scope.producto?.code,
            name: $scope.producto?.name,
            price: $scope.producto?.price,
            categoria: $scope.categoria.find(item => item.id == $scope.producto?.category)?.name
        };
        var config = {
            headers: {
                'Content-Type': 'application/json;charset=utf-8;'
            }
        };

        $http.post('api/action.php', data, config)
        .then(function(response) {
            if (response.data.error != '') {
                Swal.fire({
                    title: 'Verificar los siguientes campos: ',
                    text: response.data.error,
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                })
                return
            } else {
                Swal.fire({
                    text: response.data.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                })
                $scope.productos.push(data);
                $scope.producto = {}
            }
        })
	};

    $scope.editProduct = function(item){
        $scope.backupProducto = angular.copy(item);
        item.editar = true;
        $scope.modoEdicion = true
	};

    $scope.saveProduct = function(item){
        
        $scope.message = false;

        var data = {
            type: 'Update',
            category: item?.category,
            code: item?.code,
            name: item?.name,
            price: item?.price
        };
        var config = {
            headers: {
                'Content-Type': 'application/json;charset=utf-8;'
            }
        };

        $http.post('api/action.php', data, config)
        .then(function(response) {
            if (response.data.error.length > 0) {
                alert(response.data.error);
                return
            }
            $scope.productos.filter(element => {
                if (element.code == item.code) {
                    item.categoria = $scope.categoria.find(data => data.id == element.category)?.name
                }
                return element
            })
            $scope.message = true;
            $scope.successMessage = response.data.message;
            item.editar = false;
            $scope.modoEdicion = !$scope.modoEdicion
        })
	};

    $scope.cancelProduct = function(item){
        item.editar = false;
        $scope.modoEdicion = !$scope.modoEdicion
        angular.copy($scope.backupProducto, item);
        // $scope.itemBackup = item
	};

    $scope.deleteProduct = async function(code){
        
        $scope.message = false;

        var data = {
            type: 'Delete',
            code: code
        };
        var config = {
            headers: {
                'Content-Type': 'application/json;charset=utf-8;'
            }
        };
        const { value: text } = await Swal.fire({
            text: '¿Desea eliminar el producto seleccionado?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No',
        })

        if (text) {
            $http.post('api/action.php', data, config)
            .then(function(response) {
                Swal.fire({
                    text: response.data.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                })
                $scope.listProducts()
            })
        }
	};

    $scope.closeMessage = function(){
        $scope.message = false;
    }
});
