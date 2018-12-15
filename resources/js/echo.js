Echo.channel('reviewer')
    .listen('ReviewerParsed', (e) => {
        console.log(e);
    });
