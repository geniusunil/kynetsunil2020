var CsvToHtmlTable = CsvToHtmlTable || {};

CsvToHtmlTable = {
    init: function (options) {
        options = options || {};
        var csv_path = options.csv_path || "";
        var el = options.element || "table-container";
        var allow_download = options.allow_download || false;
        var csv_options = options.csv_options || {};
        var datatables_options = options.datatables_options || {};
        var custom_formatting = options.custom_formatting || [];
        var customTemplates = {};
        $.each(custom_formatting, function (i, v) {
            var colIdx = v[0];
            var func = v[1];
            customTemplates[colIdx] = func;
        });

        var $table = $("<table class='table table-striped table-condensed dt-table' id='" + el + "-table'></table>");
        var $containerElement = $("#" + el);
        $containerElement.empty().append($table);

        $.when($.get(csv_path)).then(
            function (data) {
                var csvData = $.csv.toArrays(data, csv_options);
                console.log(csvData);
                var $tableHead = $("<thead></thead>");
                var csvHeaderRow = csvData[0];
                var $tableHeadRow = $("<tr></tr>");
                for (var headerIdx = 0; headerIdx < csvHeaderRow.length; headerIdx++) {
                    if(headerIdx==0){
                        $tableHeadRow.append($("<th style='min-width: 150px' class='dt-th dt-unselectable dt-property'></th>").html("<input type='checkbox' onchange='toggleAll()' id='checkAll'><span>"+csvHeaderRow[headerIdx]+"</span>"));

                    }else{
                        $tableHeadRow.append($("<th style='min-width: 150px' class='dt-th dt-unselectable dt-property'></th>").text(csvHeaderRow[headerIdx]));

                    }
                }
                $tableHead.append($tableHeadRow);

                $table.append($tableHead);
                var $tableBody = $("<tbody></tbody>");

                for (var rowIdx = 1; rowIdx < csvData.length; rowIdx++) {
                    var $tableBodyRow = $("<tr></tr>");
                    for (var colIdx = 0; colIdx < csvData[rowIdx].length; colIdx++) {
                        var $tableBodyRowTd = $("<td style='min-width: 150px' class='dt-td'></td>");
                        var cellTemplateFunc = customTemplates[colIdx];
                        /* console.log(colIdx);
                        console.log(colIdx == 0); */
                        /* if (cellTemplateFunc) {
                            $tableBodyRowTd.html(cellTemplateFunc(csvData[rowIdx][colIdx]));
                        } else { */
                            if(colIdx == 0){
                                $tableBodyRowTd.html("<input type='checkbox' value='"+csvData[rowIdx][colIdx]+"' name='sport'> "+csvData[rowIdx][colIdx]);
                                // alert(csvData[rowIdx][colIdx+43]);

                            }
                            else{
                                $tableBodyRowTd.text(csvData[rowIdx][colIdx]);
                                

                            }
                        // }
                        $tableBodyRow.append($tableBodyRowTd);
                        $tableBody.append($tableBodyRow);
                    }
                }
                $table.append($tableBody);

                $table.DataTable(datatables_options);

                if (allow_download) {
                    $containerElement.append("<p><a class='btn btn-info' href='" + csv_path + "'><i class='glyphicon glyphicon-download'></i> Download as CSV</a></p>");
                }
                //$containerElement.append("<p><a class='btn btn-info send' href='" + "#" + "'><i class='glyphicon glyphicon-download'></i> Send selections</a></p>");

            });
    }
};
