let detailBookHeader;
$(document).ready(function () {
        $('#update-button').on('click', updateBookInfo);
        $('#delete-button').on('click', deleteBook);
        $('#add-book-button').on('click', addBook);
        detailBookHeader = $('#detail-book-header');
    }
);

function prepareDetailData() {
    booksData['author'] = $('#author-detail').val();
    booksData['coAuthor'] = $('#coauthors-detail').val();
    booksData['name'] = $('#name-detail').val();
    booksData['shortDescription'] = $('#desc-detail').val();
    booksData['publishDate'] = $('#date-detail').val();
}

function updateBookInfo() {
    prepareDetailData();
    $.ajax({
            url: baseAPIURL + '/book/'+bookId,
            method: 'PUT',
            data: booksData,
            success: function () {
                detailBookHeader.text(booksData['name']);
            },
            error: function (data) {
                let errorText = 'Возникла ошибка при отправке запроса. \n Попробуйте позже';
                if (!!data['responseJSON']['error']) {
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

function deleteBook() {
    $.ajax({
            url: baseAPIURL + '/book/'+bookId,
            method: 'DELETE',
            success: function () {
                window.location.replace('/admin/');
            },
            error: function (data) {
                let errorText = 'Возникла ошибка при отправке запроса. \n Попробуйте позже';
                if (!!data['responseJSON']['error']) {
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

function addBook() {
    prepareDetailData();
    $.ajax({
            url: baseAPIURL + '/book/',
            method: 'POST',
            data: booksData,
            success: function () {
                window.location.replace('/admin/');
            },
            error: function (data) {
                let errorText = 'Возникла ошибка при отправке запроса. \n Попробуйте позже';
                if (!!data['responseJSON']['error']) {
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