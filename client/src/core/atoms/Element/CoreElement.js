export const CoreElement = ({ type, props = {} }) => {
  validateArguments({ type, props });
  const element = document.createElement(type);
  element.innerHTML = '<slot></slot>';

  Object.entries(props).forEach((prop) => {
    element.setAttribute(prop[0], prop[1]);
  });

  return element;
};

const validateArguments = ({ type, props = {} }) => {
  if (typeof type !== 'string') {
    throw new TypeError('The `type` is required and parameter must be a string.');
  }

  validateProps(props);
};

const validateProps = (props) => {
  if (typeof props !== 'object') {
    throw new TypeError('The `props` parameter must be an object.');
  }

  if (props.className && typeof props.className !== 'string') {
    throw new TypeError('The `className` in `props` must be a string.');
  }
  if (props.style && typeof props.style !== 'string') {
    throw new TypeError('The `style` in `props` must be a string.');
  }
};
