document.addEventListener('DOMContentLoaded', async function () {

    if(login_type == 'admin') {
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
                {'data':'download_for'},
                {'data':'type'},
                {'data':'concatenated_files'},
                {'data' : null}
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
                },
                {
                    targets : -1,
                    render : function(d,v,row){
                        var hh = `<div class="file-item mt-4">`;
                        hh += `<a class="btn btn-sm btn-primary" href="${base_url}student/downloads?id=${row.id}"
                                        >Edit</a>&nbsp;`;
                        hh += deleteBtnRender(1,row.id);
                        hh += `</div>`;
                        return hh;
                    }
                }
            ]
        }).on('draw',function(e){
            index = 1;
            handleDeleteRows('website/delete-downloads').then( (y) => {
                listn.DataTable().ajax.reload() ;
            });
        });

        $(document).on('submit', '#downloads_form', function (e) {
            e.preventDefault();
            $.AryaAjax({
                url: 'website/add-downloads',
                data: new FormData(this)
            }).then((res) => {
                log(res)
                if (res.status) {
                    mySwal('Successful', 'Downloads Added Successfully..');
                    location.href = base_url + 'student/downloads';
                }
                showResponseError(res);
            });
        })

        const list = $('#list-images');
        var index = 1;
        var download_id = $('#download_id').val();
        list.DataTable({
            searching : false,
            dom : small_dom,
            ajax : {
                url : `${ajax_url}website/list-gallery-downloads`,
                data: {download_id : download_id}
            },
            columns : [
                {'data':null},
                {'data':'file'},
                {'data' : null}
            ],
            columnDefs : [
                {
                    targets : 0,
                    render : function(data,type,row,meta){
                        return `${ meta.row + 1 } .`;
                    }
                },
                {
                    targets : 1,
                    render : function(data,type,row){
                        if(row.extention == 'gif' || row.extention == 'jpg' || row.extention == 'jpeg' || row.extention == 'png'){
                            return `
                                    <img
                                        src="${base_url}assets/media/misc/spinner.gif"
                                        data-src="${base_url}upload/downloads/${row.file}"
                                        class="lozad rounded w-100px h-100px"
                                        alt=""
                                    />
                                    ${row.title ?? ''}
                                `;
                            } else {
                                return `<a style="cursor:pointer;" onclick="viewFile('${row.file}', '${row.extention}')"
                                        >${row.file}</a>&nbsp;`;
                            }
                        
                    }
                },
                {
                    targets : -1,
                    render : function(d,v,row){
                        return deleteBtnRender(1,row.id);
                    }
                }
            ]
        }).on('draw',function(e){
            const observer = lozad('.lozad', {
                rootMargin: '10px 0px', // syntax similar to that of CSS Margin
                threshold: 0.1, // ratio of element convergence
                enableAutoReload: true // it will reload the new image when validating attributes changes
            });
            observer.observe();
            index = 1;
            handleDeleteRows('website/delete-downloads-file').then( (y) => {
                list.DataTable().ajax.reload() ;
                listn.DataTable().ajax.reload() ;
            });
        });
        // set the dropzone container id
        const id = "#kt_dropzonejs_example_3";
        const dropzone = document.querySelector(id);
        // set the preview element template
        var previewNode = dropzone.querySelector(".dropzone-item");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);
        var myDropzone = new Dropzone(id, { // Make the whole body a dropzone
            url: ajax_url + 'website/upload-downloads-file', // Set the url for your upload script location
            parallelUploads: 20,
            maxFilesize: 2, // Max filesize in MB
            dataType: 'json',
            previewTemplate: previewTemplate,
            previewsContainer: id + " .dropzone-items", // Define the container to display the previews
            clickable: id + " .dropzone-select", // Define the element that should be used as click trigger to select files.
            success: function (file, response) {
                response = JSON.parse(response);
                if (response.status) {
                    var upload_files = $("#upload_files").val();
                    var upload_files_arr = upload_files.split('##');
                    upload_files_arr.push(response.filename);
                    $("#upload_files").val(upload_files_arr.join('##'));

                    var crossButton = file.previewElement.querySelector(".dropzone-delete");
                    crossButton.addEventListener("click", function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        // Remove file preview from Dropzone
                        myDropzone.removeFile(file);

                        // Remove the file from upload_files
                        deleteFile(response.filename);
                    });

                }
            },
            error: function (file, response) {
                var previewElement = file.previewElement;
                var errorMessageContainer = previewElement.querySelector('.dropzone-error');
                try {
                    response = JSON.parse(response);
                    errorMessageContainer.innerHTML = response.error;
                }
                catch (e) {
                    errorMessageContainer.innerHTML = response;
                }
                errorMessageContainer.style.display = 'block';
            },
        });
        // myDropzone.
        myDropzone.on("addedfile", function (file) {
            // Hookup the start button
            const dropzoneItems = dropzone.querySelectorAll('.dropzone-item');
            dropzoneItems.forEach(dropzoneItem => {
                dropzoneItem.style.display = '';
            });
        });
        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function (progress) {
            const progressBars = dropzone.querySelectorAll('.progress-bar');
            progressBars.forEach(progressBar => {
                progressBar.style.width = progress + "%";
            });
        });
        myDropzone.on("sending", function (file) {
            // Show the total progress bar when upload starts
            const progressBars = dropzone.querySelectorAll('.progress-bar');
            progressBars.forEach(progressBar => {
                progressBar.style.opacity = "1";
            });
        });
        // Hide the total progress bar when nothing"s uploading anymore
        myDropzone.on("complete", function (progress) {
            // log('complete');
            // log(progress);
            const progressBars = dropzone.querySelectorAll('.dz-complete');
            setTimeout(function () {
                progressBars.forEach(progressBar => {
                    progressBar.querySelector('.progress-bar').style.opacity = "0";
                    progressBar.querySelector('.progress').style.opacity = "0";
                });
            }, 300);
        });
        myDropzone.on('queuecomplete', function(){
            toastr.success('Process Complete Successfull');
            //list.DataTable().ajax.reload();
        });

        function deleteFile(filename) {
            var upload_files = $("#upload_files").val(); // Get the current value of the input
            if (upload_files) {
                var upload_files_arr = upload_files.split('##'); // Convert the string to an array
                var updated_files_arr = upload_files_arr.filter(file => file !== filename); // Remove the specific file
                $("#upload_files").val(updated_files_arr.join('##')); // Update the input value
            }
        }

    } else {
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
    }

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

    window.downloadFile = function(fileName) {
        var fileUrl = `${base_url}upload/downloads/${fileName}`;
        var link = document.createElement('a');
        link.href = fileUrl;
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

})