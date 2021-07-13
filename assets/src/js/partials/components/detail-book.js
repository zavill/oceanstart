let detailBookHeader;
$(document).ready(function () {
        $('#update-button').on('click', updateBookInfo);
        $('#delete-button').on('click', deleteBook);
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

function prepareBookDOM(data) {
    let coAuthorsText = '';
    if (data.coAuthor) {
        let coAuthorsLength = data['coAuthor'].length - 1;
        data.coAuthor.forEach(function (elem, index) {
            coAuthorsText += elem;
            if (index === coAuthorsLength) {
                coAuthorsText += '. ';
            } else {
                coAuthorsText += ', ';
            }
        });
    }
    let openTag, closeTag;
    if (isAdmin) {
        openTag = '<li><a href="/admin/book/'+data['id']+'">';
        closeTag = '</a></li>';
    } else {
        openTag = '<li>';
        closeTag = '</li>';
    }
    return openTag +
        data['name'] + '. ' +
        data['author'] + '. ' +
        coAuthorsText +
        data['shortDescription'] + '. ' +
        data['publishDate'] + '.'
    closeTag;
}