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
    return '<li>' +
        data['name'] + '. ' +
        data['author'] + '. ' +
        coAuthorsText +
        data['shortDescription'] + '. ' +
        data['publishDate'] + '.'
    '</li>';
}