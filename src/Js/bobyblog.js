function getPosts(orderBy, direction, offset, filter = null) {
    var postContainer = $('.postContainer');

    $.ajax({
            method: 'GET',
            url: '/post/get',
            data: {filter: filter, orderBy: orderBy, direction: direction, offset: offset},
            beforeSend: () => {
                //startLoading();
                if(orderBy !== $('#currentOrderBy').val() || offset === 0){
                    $('#currentOrderBy').val(orderBy);
                    postContainer.html('');
                } else if (orderBy === $('#currentOrderBy').val()){
                    postContainer.html('');
                }
                $('#currentDirection').val($('#currentDirection').val() === 'ASC' ? 'DESC':'ASC');
            }
    })
    .done((response) => {
        postContainer.append(response);
    })
    .always(() => {
        //stopLoading();
        bindClickListener();
        updateViewExplained(orderBy, direction, offset, filter);
    });
}

function bindClickListener(){
    $('a#happenedDate').click(() => {
        getPosts('happenedDate', $('#currentDirection').val(), $('#currentPage').val());
    });

    $('a#creationDate').click(() => {
        getPosts('creationDate', $('#currentDirection').val(), $('#currentPage').val());
    });

    $('.tags .tag a').click((e) => {
        let tagName = e.currentTarget.outerText.slice(1); // remove first char
        getPosts('creationDate', $('#currentDirection').val(), $('#currentPage').val(), tagName);
    });
}

function updateViewExplained(orderBy, direction, offset, filter = null) {
    let text = '';

    text += orderBy === 'happenedDate ' ? 'Date of happening' : 'Posting date';
    text += direction === 'ASC ' ? ' ascending' : ' descending';
    text += ', page '+(parseInt(offset)+1);
    text += filter !== null ? ' - filter by '+filter : '';

    $('.currentView').text(text);
}

bindClickListener();

$('a#happenedDate').click();