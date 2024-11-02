import { CoreElement } from '../Element/CoreElement.js';

export const CoreButton = (props) => {
  return CoreElement({
    type: 'button',
    props: props,
  });
};
