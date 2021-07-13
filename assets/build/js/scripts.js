let baseAPIURL = '/api';
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

function deleteAuthor() {
    $.ajax({
            url: baseAPIURL + '/author/' + authorId,
            method: 'DELETE',
            success: function () {
                window.location.replace('/admin/authors/');
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

function addAuthor() {
    prepareDetailAuthorData();
    $.ajax({
            url: baseAPIURL + '/author/',
            method: 'POST',
            data: authorData,
            success: function () {
                window.location.replace('/authors/');
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
let booksBlock;
let booksData = {};
$(document).ready(function () {
        $('#filter-button').on('click', updateBookList);
        $('#sort-select').on('change', updateBookList);
        booksBlock = $('#books-block');
    }
);

function prepareData() {
    booksData['sortField'] = $('#sort-select').val();
    booksData['author'] = $('#author-select').val();
    booksData['coAuthor'] = $('#co-author-select').val();
    booksData['name'] = $('#name-filter').val();
    booksData['shortDescription'] = $('#desc-filter').val();
    booksData['publishDate'] = $('#date-filter').val();
}

function updateBookList() {
    prepareData();
    $.ajax({
            url: baseAPIURL + '/book',
            method: 'GET',
            data: booksData,
            success: function (response) {
                data = response['data'];
                console.log(data);
                booksBlock.empty();
                if (data.length > 0) {
                    data.forEach(function (book) {
                        let dom = prepareBookDOM(book);
                        booksBlock.append(dom);
                    })
                } else {
                    booksBlock.append('<p>По вашему фильтру книг не найдено.</p>');
                }
            },
            error: function (data) {
                console.warn(data);
                console.log('error');
                $.toast({
                    text: "Возникла ошибка при отправке запроса. \n Попробуйте позже",
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