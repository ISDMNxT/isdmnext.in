document.addEventListener('DOMContentLoaded', async function () {
    const listn = $('#list-downloads');
    var index = 1;
    listn.DataTable({
        searching : true,
        dom : small_dom,
        ajax : {
            url : `${ajax_url}website/list-downloads`,
            
        },
        columns : [
            {'data':null},
            {'data':'title'},
            {'data':'description'},
            {'data':'type'},
            {'data':'concatenated_files'},
        ],
        columnDefs : [
            {
                targets : 0,
                render : function(data,type,row,meta){
                    return `${ meta.row + 1 } .`;
                }
            },
            {
                targets : 3,
                render : function(data,type,row,meta){
                    if(row.type == 'marketing'){
                        return `Marketing Material`;
                    } else {
                        return `Operational  Material`;
                    }
                    
                }
            },
            {
                targets: 4,
                render: function(data, type, row) {
                    if (row.concatenated_files && row.files_extention) {
                        var files = row.concatenated_files.split('##');
                        var extensions = row.files_extention.split('##');

                        var fileHtml = `<div class="file-item mt-4">`;
                        files.forEach((file, index) => {
                            var extension = extensions[index] || 'Unknown'; // Handle missing extensions
                            fileHtml += `<button class="btn btn-sm btn-success view-btn" onclick="viewFile('${file}', '${extension}')"
                                    >View</button>&nbsp;`;
                        });
                        fileHtml += `</div>`;

                        return fileHtml;
                    }

                    // Placeholder when no files are present
                    return ``;
                }
            }
        ]
    });

    window.viewFile = function(fileName, extension) {
        var fileUrl = `${base_url}upload/downloads/${fileName}`;
        if (['jpg', 'jpeg', 'png', 'gif', 'pdf'].includes(extension.toLowerCase())) {
            window.open(fileUrl, '_blank');
        } else {
            var link = document.createElement('a');
            link.href = fileUrl;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    };
})