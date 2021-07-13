let detailAuthorHeader;
let authorData = {};
$(document).ready(function () {
        $('#update-author-button').on('click', updateAuthorInfo);
        $('#delete-author-button').on('click', deleteAuthor);
        $('#add-author-button').on('click', addAuthor);
        detailAuthorHeader = $('#detail-author-header');
    }
);

function prepareDetailAuthorData() {
    authorData['name'] = $('#author-name').val();
}

function updateAuthorInfo() {
    prepareDetailAuthorData();
    $.ajax({
            url: baseAPIURL + '/author/' + authorId,
            method: 'PUT',
            data: authorData,
            success: function () {
                detailAuthorHeader.text(authorData['name']);
            },
            error: function (data) {
                console.log(data);
                let errorText = 'Возникла ошибка при отправке запроса. \n Попробуйте позже';
                if (!!data['responseJSON'] && !!data['responseJSON']['error']) {
                    errorText = data['responseJSON']['error'];
                }
                $.toast({
                    text: errorText,
                    bgColor: '#c0392b',
                    textColor: '#bdc3c7',
                    allowToastClose: true,
                    position: 'bottom-right',
                    icon: 'error'
                })
            }
        }
    )
}

function deleteAuthor() {
    $.ajax({
            url: baseAPIURL + '/author/' + authorId,
            method: 'DELETE',
            success: function () {
                window.location.replace('/admin/authors/');
            },
            error: function (data) {
                let errorText = 'Возникла ошибка при отправке запроса. \n Попробуйте позже';
                if (!!data['responseJSON'] && !!data['responseJSON']['error']) {
                    errorText = data['responseJSON']['error'];
                }
                $.toast({
                    text: errorText,
                    bgColor: '#c0392b',
                    textColor: '#bdc3c7',
                    allowToastClose: true,
                    position: 'bottom-right',
                    icon: 'error'
                })
            }
        }
    )
}

function addAuthor() {
    prepareDetailAuthorData();
    $.ajax({
            url: baseAPIURL + '/author/',
            method: 'POST',
            data: authorData,
            success: function () {
                window.location.replace('/admin/authors/');
            },
            error: function (data) {
                let errorText = 'Возникла ошибка при отправке запроса. \n Попробуйте позже';
                if (!!data['responseJSON'] && !!data['responseJSON']['error']) {
                    errorText = data['responseJSON']['error'];
                }
                $.toast({
                    text: errorText,
                    bgColor: '#c0392b',
                    textColor: '#bdc3c7',
                    allowToastClose: true,
                    position: 'bottom-right',
                    icon: 'error'
                })
            }
        }
    )
}
