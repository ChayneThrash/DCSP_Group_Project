function isUserValid(username) {
    return (username.length <= 50) && (username.split(' ').length === 1);
}