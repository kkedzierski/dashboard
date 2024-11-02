import { CoreElement } from '../Element/CoreElement.js';

export const CoreInput = (props) => {
  return CoreElement({
    type: 'input',
    props: props,
  });
};
