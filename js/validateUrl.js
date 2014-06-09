
function validateUrl(str) {
	var v = new RegExp();
	v.compile("^((ht|f)tp(s?)\:\/\/|~/|/)?[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");
	if(!v.test(str)) {
		return false;
	} else {
		return true;
	}
}