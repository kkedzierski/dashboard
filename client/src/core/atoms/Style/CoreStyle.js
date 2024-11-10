import { CoreElement } from '../Element/CoreElement.js';

export const CoreStyle = (props) => {
  return CoreElement({
    type: 'style',
    props: props,
  });
};
