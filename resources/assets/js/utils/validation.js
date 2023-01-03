class Validation {
  static isValidString(value) {
    if (value.length <= 0) {
      return false;
    } else {
      return true;
    }
  }
  static isLength(value, length) {
    if (value.length > length) {
      return false;
    } else {
      return true;
    }
  }
  static isValidEmail(value) {
    if (value.length <= 0) {
      return false;
    }
    return value.match(
      // eslint-disable-next-line max-len
      /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/
    );
  }
  static isValidPassword(value) {
    if (value.length <= 7) {
      return false;
    }
    const matchNumber = value.match(/\d/g);
    const matchCapital = value.match(/[A-Z]/g);
    // eslint-disable-next-line no-useless-escape
    const matchSpecial = value.match(/[ !""#$%&'()*+,-./\:;?@[\]^_`{|}~"]/g);
    const matchLowercase = value.match(/[a-z]/g);
    return matchNumber && matchCapital && matchSpecial && matchLowercase;
  }
}

export default Validation;
