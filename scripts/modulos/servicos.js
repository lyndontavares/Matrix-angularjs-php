/**
 * 
 * factory
 */
app.factory('fecthData', ['$rootScope', '$http', function ($r, $h) {

        var getFecthData = function (time, expe) {
            var p = $r.HOST_LOCAL + 'rest/item/grafico/{"time":"' + time + '","id":"' + expe + '"}';
            console.log(p);
            $h.get(p)
                    .success(function (response) {
                        return response.records;
                    })
        }
        return {
            getFecthData: function (time, expe) {
                return getFecthData(time, expe);
            }
        }

    }]);