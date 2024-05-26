export const accessId = (rule: any, value: any, callback: any) => {
  if (!value) {
    return callback(new Error('필수입력입니다'));
  }

  if (!/^[a-zA-Z0-9_-]+$/.test(value)) {
    callback(new Error('영문 또는 숫자로 만들어주세요'));
  } else {
    callback();
  }
};
